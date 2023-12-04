create schema if not exists lbaw2334;

SET DateStyle TO European;

--====================================--
--          Drop old schema           --
--====================================--

DROP TABLE IF EXISTS user_ CASCADE;
DROP TABLE IF EXISTS post_ CASCADE;
DROP TABLE IF EXISTS group_ CASCADE;
DROP TABLE IF EXISTS message_ CASCADE;
DROP TABLE IF EXISTS comment_ CASCADE;
DROP TABLE IF EXISTS notification_ CASCADE;
DROP TABLE IF EXISTS admin_ CASCADE;
DROP TABLE IF EXISTS owner_ CASCADE;
DROP TABLE IF EXISTS belongs_ CASCADE;
DROP TABLE IF EXISTS user_notification CASCADE;
DROP TABLE IF EXISTS post_notification CASCADE;
DROP TABLE IF EXISTS request_ CASCADE;
DROP TABLE IF EXISTS follows_ CASCADE;
DROP TABLE IF EXISTS post_likes CASCADE;
DROP TABLE IF EXISTS comment_likes CASCADE;
DROP TABLE IF EXISTS saved_post CASCADE;

DROP TYPE IF EXISTS post_content_types CASCADE;
DROP TYPE IF EXISTS user_notification_types CASCADE;
DROP TYPE IF EXISTS post_notification_types CASCADE;

-- functions ->

--====================================--
--               Types                --
--====================================--

CREATE TYPE post_content_types AS ENUM ('image', 'video');
CREATE TYPE user_notification_types AS ENUM ('started_following', 'request_follow', 'accepted_follow');
CREATE TYPE post_notification_types AS ENUM ('liked_post', 'commented_post');

--====================================--
--               Tables               --
--====================================--

CREATE TABLE user_ (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    name_ TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_ TEXT NOT NULL,
    private_ BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE post_ (
    postID SERIAL PRIMARY KEY,
    content_ TEXT NOT NULL, 
    description_ TEXT,
    likes_ INTEGER DEFAULT 0,
    comments_ INTEGER DEFAULT 0, 
    time_ TIMESTAMP NOT NULL,
    created_by INTEGER NOT NULL REFERENCES user_ (id) ON UPDATE CASCADE,
    content_type post_content_types
);

CREATE TABLE group_ (
    groupID SERIAL PRIMARY KEY, 
    name_ TEXT NOT NULL, 
    description_ TEXT DEFAULT NULL              -- default value must be add a description
);

CREATE TABLE message_ (
    messageID SERIAL PRIMARY KEY, 
    description_ TEXT NOT NULL, 
    time_ TIMESTAMP NOT NULL, 
    sender INTEGER NOT NULL REFERENCES user_ (id) ON UPDATE CASCADE, 
    receiver INTEGER REFERENCES user_ (id) ON UPDATE CASCADE, 
    sent_to INTEGER REFERENCES group_ (groupID) ON UPDATE CASCADE, 
    message_replies INTEGER DEFAULT NULL REFERENCES message_ (messageID) ON UPDATE CASCADE          -- tirar default null
);

CREATE TABLE comment_ (
    commentID SERIAL PRIMARY KEY, 
    description_ TEXT NOT NULL,
    likes_ INTEGER DEFAULT 0, 
    time_ TIMESTAMP NOT NULL,
    id INTEGER NOT NULL REFERENCES user_ (id) ON UPDATE CASCADE,
    postID INTEGER NOT NULL REFERENCES post_ (postID) ON UPDATE CASCADE,
    comment_replies INTEGER DEFAULT NULL REFERENCES comment_ (commentID) ON UPDATE CASCADE          -- tirar default null
);

CREATE TABLE notification_ (
    notificationID SERIAL PRIMARY KEY,
    description_ TEXT NOT NULL, 
    time_ TIMESTAMP NOT NULL,
    notifies INTEGER NOT NULL REFERENCES user_ (id),
    sends_notif INTEGER NOT NULL REFERENCES user_ (id)
);

CREATE TABLE admin_ (
    id SERIAL PRIMARY KEY REFERENCES user_ (id) ON UPDATE CASCADE
);

CREATE TABLE owner_ (
    id INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    groupID INTEGER REFERENCES group_ (groupID) ON UPDATE CASCADE,
    PRIMARY KEY (id, groupID)
);

CREATE TABLE belongs_ (
    id INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    groupID INTEGER REFERENCES group_ (groupID) ON UPDATE CASCADE,
    PRIMARY KEY (id, groupID)
);

CREATE TABLE user_notification (
    notificationID INTEGER PRIMARY KEY REFERENCES notification_ (notificationID) ON UPDATE CASCADE,
    id INTEGER NOT NULL REFERENCES user_ (id), 
    notification_type user_notification_types NOT NULL
);

CREATE TABLE post_notification (
    notificationID INTEGER PRIMARY KEY REFERENCES notification_ (notificationID) ON UPDATE CASCADE,
    postID INTEGER NOT NULL REFERENCES post_ (postID) ON UPDATE CASCADE, 
    notification_type post_notification_types NOT NULL
);

CREATE TABLE request_ (
    senderID INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    receiverID INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    PRIMARY KEY (senderID, receiverID)
);

CREATE TABLE follows_ (
    followerID INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    followedID INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    PRIMARY KEY (followerID, followedID)
);

CREATE TABLE post_likes (
    id INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    postID INTEGER REFERENCES post_ (postID) ON UPDATE CASCADE,
    PRIMARY KEY (id, postID)
);

CREATE TABLE comment_likes (
    id INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    commentID INTEGER REFERENCES comment_ (commentID) ON UPDATE CASCADE,
    PRIMARY KEY (id, commentID)
);

CREATE TABLE saved_post (
    id INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    postID INTEGER REFERENCES post_ (postID) ON UPDATE CASCADE,
    PRIMARY KEY (id, postID)
);

--====================================--
--              Indexes               --
--====================================--

CREATE INDEX Author_Post ON post_ USING hash (created_by);

CREATE INDEX Notified_User_Notification ON notification_ USING btree (notifies);
CLUSTER notification_ USING Notified_User_Notification;

CREATE INDEX Emitter_User_Notification ON notification_ USING btree (sends_notif);
CLUSTER notification_ USING Emitter_User_Notification;

CREATE INDEX Comment_Post ON comment_ USING btree (postID);


--====================================--
--            FTS INDEXES             --
--====================================--

-- ### Index IDX05 for "User"

-- - **Index relation:** "User"
-- - **Index Attributes:** "username, name"
-- - **Index Type:** GIN (Generalized Inverted Index)
-- - **Clustering:** No
-- - **Justification:** To provide full-text search features to look for users based on matching usernames and names.

ALTER TABLE "user_"
ADD COLUMN user_tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.user_tsvectors = (
         setweight(to_tsvector('english', NEW.username), 'A') ||
         setweight(to_tsvector('english', NEW.name_), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.username <> OLD.username OR NEW.name_ <> OLD.name_) THEN
           NEW.user_tsvectors = (
             setweight(to_tsvector('english', NEW.username), 'A') ||
             setweight(to_tsvector('english', NEW.name_), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
    BEFORE INSERT OR UPDATE ON "user_"
    FOR EACH ROW
    EXECUTE FUNCTION user_search_update();

CREATE INDEX user_search_idx ON "user_" USING GIN (user_tsvectors);


-- ### Index IDX06 for "Group"

-- - **Index relation:** "Group"
-- - **Index Attributes:** "name, description"
-- - **Index Type:** GIN (Generalized Inverted Index)
-- - **Clustering:** No
-- - **Justification:** To provide full-text search features to look for groups based on matching group names and descriptions.

ALTER TABLE "group_"
ADD COLUMN group_tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION group_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.group_tsvectors = (
         setweight(to_tsvector('english', NEW.name_), 'A') ||
         setweight(to_tsvector('english', NEW.description_), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.name_ <> OLD.name_ OR NEW.description_ <> OLD.description_) THEN
           NEW.group_tsvectors = (
             setweight(to_tsvector('english', NEW.name_), 'A') ||
             setweight(to_tsvector('english', NEW.description_), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;


CREATE TRIGGER group_search_update
    BEFORE INSERT OR UPDATE ON "group_"
    FOR EACH ROW
    EXECUTE FUNCTION group_search_update();

CREATE INDEX group_search_idx ON "group_" USING GIN (group_tsvectors);

-- ### Index IDX07 for "Post"

-- - **Index relation:** "Post"
-- - **Index Attributes:** "content, description"
-- - **Index Type:** GIN (Generalized Inverted Index)
-- - **Clustering:** No
-- - **Justification:** To provide full-text search features to look for posts based on matching post content and descriptions.

ALTER TABLE "post_"
ADD COLUMN post_tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.post_tsvectors = (
         setweight(to_tsvector('english', NEW.content_), 'A') ||
         setweight(to_tsvector('english', NEW.description_), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.content_ <> OLD.content_ OR NEW.description_ <> OLD.description_) THEN
           NEW.post_tsvectors = (
             setweight(to_tsvector('english', NEW.content_), 'A') ||
             setweight(to_tsvector('english', NEW.description_), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER post_search_update
    BEFORE INSERT OR UPDATE ON "post_"
    FOR EACH ROW
    EXECUTE FUNCTION post_search_update();

CREATE INDEX post_search_idx ON "post_" USING GIN (post_tsvectors); 


-- ### Index IDX08 for "Message"

-- - **Index relation:** "Message"
-- - **Index Attributes:** "description"
-- - **Index Type:** GIN (Generalized Inverted Index)
-- - **Clustering:** No
-- - **Justification:** To provide full-text search features to look for messages based on matching message descriptions.

ALTER TABLE "message_"
ADD COLUMN message_tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION message_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.message_tsvectors = setweight(to_tsvector('english', NEW.description_), 'A');
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.description_ <> OLD.description_) THEN
           NEW.message_tsvectors = setweight(to_tsvector('english', NEW.description_), 'A');
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER message_search_update
    BEFORE INSERT OR UPDATE ON "message_"
    FOR EACH ROW
    EXECUTE FUNCTION message_search_update();

CREATE INDEX message_search_idx ON "message_" USING GIN (message_tsvectors);


-- ### Index IDX09 for "Comment"

-- - **Index relation:** "Comment"
-- - **Index Attributes:** "description"
-- - **Index Type:** GIN (Generalized Inverted Index)
-- - **Clustering:** No
-- - **Justification:** To provide full-text search features to look for comments based on matching comment descriptions.

ALTER TABLE "comment_"
ADD COLUMN comment_tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION comment_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.comment_tsvectors = setweight(to_tsvector('english', NEW.description_), 'A');
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.description_ <> OLD.description_) THEN
           NEW.comment_tsvectors = setweight(to_tsvector('english', NEW.description_), 'A');
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER comment_search_update
    BEFORE INSERT OR UPDATE ON "comment_"
    FOR EACH ROW
    EXECUTE FUNCTION comment_search_update();

CREATE INDEX comment_search_idx ON "comment_" USING GIN (comment_tsvectors);



--====================================--
--              Triggers              --
--====================================--

--TRIGGER01
-- A user cannot like his/her own posts (Business rule BR10).

CREATE FUNCTION verify_self_liking_post() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM post_ WHERE NEW.postID = post_.postID AND NEW.id = post_.created_by) 
        THEN RAISE EXCEPTION 'A user cannot like their own posts';
    END IF;

    RETURN NEW;
END 
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_self_liking_post
    BEFORE INSERT OR UPDATE ON post_likes
    FOR EACH ROW
    EXECUTE PROCEDURE verify_self_liking_post();


--TRIGGER02
--A user cannot like his/her own comments.

CREATE FUNCTION verify_self_liking_comment() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM comment_ WHERE NEW.commentID = comment_.commentID AND NEW.id = comment_.id) 
        THEN RAISE EXCEPTION 'A user cannot like their own comments';
    END IF;

    RETURN NEW;
END 
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_self_liking_comment
    BEFORE INSERT OR UPDATE ON comment_likes
    FOR EACH ROW
    EXECUTE PROCEDURE verify_self_liking_comment();


--TRIGGER03
--A user can only like a post once.

CREATE FUNCTION verify_post_likes() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM post_likes WHERE NEW.id = post_likes.id AND NEW.postID = post_likes.postID) 
        THEN RAISE EXCEPTION 'A user can only like a post once';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_post_likes
    BEFORE INSERT OR UPDATE ON post_likes
    FOR EACH ROW
    EXECUTE PROCEDURE verify_post_likes();


--TRIGGER04
--A user can only like a comment once.

CREATE FUNCTION verify_comment_likes() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM comment_likes WHERE NEW.id = id AND NEW.commentID = commentID) 
        THEN RAISE EXCEPTION 'A user can only like a comment once';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_comment_likes
    BEFORE INSERT OR UPDATE ON comment_likes
    FOR EACH ROW
    EXECUTE PROCEDURE verify_comment_likes();


--TRIGGER05
-- A user cannot follow itself.

CREATE FUNCTION verify_self_follow() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.followerID = NEW.followedID THEN
        RAISE EXCEPTION 'A user can not follow itself';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_self_follow
    BEFORE INSERT OR UPDATE ON follows_
    FOR EACH ROW
    EXECUTE PROCEDURE verify_self_follow();


--TRIGGER06
--A user can only comment on posts from public users or posts from users they follow.

CREATE FUNCTION verify_comment() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM user_, post_ WHERE NEW.postID = post_.postID AND post_.created_by = user_.id AND user_.private_ )
        AND NOT EXISTS (SELECT * FROM post_,follows_ WHERE NEW.postID = post_.postID AND NEW.id = follows_.followerID AND follows_.followedID = post_.created_by) 
        THEN RAISE EXCEPTION 'A user can only comment on posts from public users or users they follow';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_comment
    BEFORE INSERT OR UPDATE ON comment_
    FOR EACH ROW
    EXECUTE PROCEDURE verify_comment();


--TRIGGER07
--A group owner is also a member of your group.

CREATE FUNCTION group_owner() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NOT EXISTS ( SELECT  * FROM belongs_ WHERE NEW.id = belongs_.id AND NEW.groupID = belongs_.groupID) 
        THEN RAISE EXCEPTION 'A group owner must also be a member of the group';
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER group_owner
    BEFORE INSERT OR UPDATE ON owner_
    FOR EACH ROW
    EXECUTE PROCEDURE group_owner();


--TRIGGER08
--A user cannot request to follow a user that he/she already follow.

CREATE FUNCTION check_follow_request() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM follows_ WHERE NEW.followerID = follows_.followerID AND NEW.followedID = follows_.followedID)
        THEN RAISE EXCEPTION 'Can not make a follow request to someone you already follow';
    END IF;
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_follow_request
    BEFORE INSERT ON follows_
    FOR EACH ROW
    EXECUTE PROCEDURE check_follow_request();


--TRIGGER09
--A user cannot request to follow themselves.

CREATE FUNCTION verify_self_follow_req() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.followerID = NEW.followedID 
        THEN RAISE EXCEPTION 'A user can not request to follow themselves';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_self_follow_req
    BEFORE INSERT OR UPDATE ON follows_
    FOR EACH ROW
    EXECUTE PROCEDURE verify_self_follow_req();


--TRIGGER10
--When deleting a post it also deletes its comments, subcomments, likes and notifications.

CREATE FUNCTION delete_post_action() RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM post_likes WHERE OLD.postID = post_likes.postID;
    DELETE FROM post_notification WHERE OLD.postID = post_notification.postID;
    DELETE FROM comment_ WHERE OLD.postID IN (SELECT postID FROM comment_ WHERE OLD.postID = comment_.postID OR OLD.postID = comment_.commentID);

    RETURN OLD;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER delete_post_action
    BEFORE DELETE ON post_
    FOR EACH ROW
    EXECUTE PROCEDURE delete_post_action();


--TRIGGER11
--When deleting a comment it also deletes its likes, subcomments and notifications.

CREATE FUNCTION delete_comment_action() RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM comment_likes WHERE OLD.commentID = comment_likes.commentID;
    DELETE FROM post_notification WHERE OLD.commentID = post_notification.postID;
    DELETE FROM comment_ WHERE OLD.commentID = comment_.commentID;

    RETURN OLD;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER delete_comment_action
    BEFORE DELETE ON comment_
    FOR EACH ROW
    EXECUTE PROCEDURE delete_comment_action();