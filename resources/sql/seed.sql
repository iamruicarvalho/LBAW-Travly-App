DROP SCHEMA IF EXISTS lbaw2334 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2334;
SET search_path TO lbaw2334;
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
CREATE TYPE user_notification_types AS ENUM ('started_following', 'unfollow', 'unfriend', 'request_follow', 'accepted_follow', 'rejected_follow');
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
    private_ BOOLEAN NOT NULL DEFAULT TRUE,
    description_ TEXT DEFAULT 'Add description',
    location TEXT DEFAULT 'Add location',
    countries_visited INT DEFAULT 0,
    header_picture VARCHAR(256) DEFAULT 'https://i.pinimg.com/originals/3e/64/54/3e6454934ac0836b8a3112008b3a5c0a.jpg', 
    profile_picture VARCHAR(256) DEFAULT 'https://i.pinimg.com/originals/10/d9/6e/10d96e8abddf88f21c8108e5158c80bc.jpg', 
    remember_token VARCHAR(100) NULL 
);

CREATE TABLE group_ (
    groupID SERIAL PRIMARY KEY, 
    name_ TEXT NOT NULL, 
    description_ TEXT DEFAULT NULL              -- default value must be add a description
);

CREATE TABLE post_ (
    postID SERIAL PRIMARY KEY,
    content_ VARCHAR(256), 
    description_ TEXT NOT NULL,
    likes_ INTEGER DEFAULT 0,
    comments_ INTEGER DEFAULT 0, 
    time_ TIMESTAMP NOT NULL,
    created_by INTEGER NOT NULL REFERENCES user_ (id) ON UPDATE CASCADE,
    groupID INTEGER REFERENCES group_ (groupID) ON UPDATE CASCADE,
    content_type post_content_types
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
    id INTEGER NOT NULL REFERENCES user_ (id) ON UPDATE CASCADE,                        -- comment author
    postID INTEGER NOT NULL REFERENCES post_ (postID) ON UPDATE CASCADE,
    comment_replies INTEGER DEFAULT NULL REFERENCES comment_ (commentID) ON UPDATE CASCADE          -- tirar default null
);

CREATE TABLE notification_ (
    notificationid SERIAL PRIMARY KEY,
    description_ TEXT NOT NULL, 
    time_ TIMESTAMP NOT NULL,
    seen BOOLEAN DEFAULT FALSE,
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
    notificationid INTEGER PRIMARY KEY REFERENCES notification_ (notificationid) ON UPDATE CASCADE,
    id INTEGER NOT NULL REFERENCES user_ (id), 
    notification_type user_notification_types NOT NULL
);

CREATE TABLE post_notification (
    notificationid INTEGER PRIMARY KEY REFERENCES notification_ (notificationid) ON UPDATE CASCADE,
    postID INTEGER NOT NULL REFERENCES post_ (postID) ON UPDATE CASCADE, 
    notification_type post_notification_types NOT NULL
);

CREATE TABLE request_ (
    senderid INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    receiverid INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    PRIMARY KEY (senderid, receiverid)
);

CREATE TABLE follows_ (
    followerid INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    followedid INTEGER REFERENCES user_ (id) ON UPDATE CASCADE,
    PRIMARY KEY (followerid, followedid)
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

    RETURN OLD;
END;
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER delete_comment_action
    BEFORE DELETE ON comment_
    FOR EACH ROW
    EXECUTE PROCEDURE delete_comment_action();

--====================================--
--            Populate DB             --
--====================================--

INSERT INTO user_(username, name_, email, password_, private_) VALUES
            ('andrdr28', 'Andre', 'andr28@gmail.com', '$2y$10$WKYx7hG2PyC9rnadSKUAD.oMISWkBGWW32DKtayWxjWjQy8ltelRC', False),
            ('georgekatie', 'George', 'georgekatie350@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('hannahquinn', 'Hannah', 'hannahquinn842@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('yarasam', 'Yara', 'yarasam976@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('meganwendy', 'Megan', 'meganwendy463@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('nathanxander', 'Nathan', 'nathanxander785@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('quinnsam', 'Quinn', 'quinnsam18@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('jackzane', 'Jack', 'jackzane328@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszdF424gbL3u', True),
            ('zaneeva', 'Zane', 'zaneeva990@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('zanesam', 'Zane', 'zanesam914@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('alicewendy', 'Alice', 'alicewendy126@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('liamkatie', 'Liam', 'liamkatie67@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('yaracarlos', 'Yara', 'yaracarlos799@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('hannahzane', 'Hannah', 'hannahzane619@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('liamzane', 'Liam', 'liamzane311@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('zanehannah', 'Zane', 'zanehannah195@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('oliviaalice', 'Olivia', 'oliviaalice475@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('zanebob', 'Zane', 'zanebob976@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('tomtom', 'Tom', 'tomtom684@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('davidquinn', 'David', 'davidquinn754@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('liamdavid', 'Liam', 'liamdavid227@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('tomyara', 'Tom', 'tomyara219@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('oliviabob', 'Olivia', 'oliviabob192@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('rachelhannah', 'Rachel', 'rachelhannah973@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('racheljack', 'Rachel', 'racheljack573@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('samdavid', 'Sam', 'samdavid355@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('jackjack', 'Jack', 'jackjack82@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('nathanolivia', 'Nathan', 'nathanolivia518@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('liamquinn', 'Liam', 'liamquinn677@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('jackbob', 'Jack', 'jackbob786@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('oliviauma', 'Olivia', 'oliviauma108@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('tombob', 'Tom', 'tombob848@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('victoryara', 'Victorya', 'victoryara612@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('mikejagger', 'Mike', 'mikejagger908@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('georgerachel', 'George', 'georgerachel363@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('liamivan', 'Liam', 'liamivan331@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('katieolivia', 'Katie', 'katieolivia38@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('meganpeter', 'Megan', 'meganpeter830@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('georgemegan', 'George', 'georgemegan607@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('mickmonterrey', 'Mick', 'mickmonterrey@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('rachelmegan', 'Rachel', 'rachelmegan896@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('oliviageorge', 'Olivia', 'oliviageorge754@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('davidjack', 'David', 'davidjack408@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('peterkatie', 'Peter', 'peterkatie191@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('tomeva', 'Tom', 'tomeva205@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('fionaquinn', 'Fiona', 'fionaquinn493@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('davidcarlos', 'David', 'davidcarlos909@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('samtom', 'Sam', 'samtom78@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('mikedavis', 'Mike', 'mikedavis@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('peteryara', 'Peter', 'peteryara541@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('victorcarlos', 'Victor', 'victorcarlos518@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('ivansam', 'Ivan', 'ivansam811@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('fionaliam', 'Fiona', 'fionaliam901@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('peteralice', 'Peter', 'peteralice614@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('wendykatie', 'Wendy', 'wendykatie654@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('liampeter', 'Liam', 'liampeter806@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('sammegan', 'Sam', 'sammegan753@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('aliceeva', 'Alice', 'aliceeva176@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('victorrachel', 'Victor', 'victorrachel214@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('georgedavid', 'George', 'georgedavid857@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('alicemegan', 'Alice', 'alicemegan232@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('quinnivan', 'Quinn', 'quinnivan690@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('hannahgeorge', 'Hannah', 'hannahgeorge489@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('liamnathan', 'Liam', 'liamnathan348@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('jackkatie', 'Jack', 'jackkatie72@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('xanderzane', 'Xander', 'xanderzane447@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('fionaxander', 'Fiona', 'fionaxander858@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('quinnbob', 'Quinn', 'quinnbob335@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('tommegan', 'Tom', 'tommegan928@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('jackfiona', 'Jack', 'jackfiona763@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('markoliver', 'Mark', 'markoliver43@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('olivedavid', 'Olive', 'olivedavid4890@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('nathanyara', 'Nathan', 'nathanyara617@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('peternathan', 'Peter', 'peternathan918@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('katiequinn', 'katie', 'katiequinn734@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('tomxander', 'Tom', 'tomxander563@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('tomuma', 'Tom', 'tomuma592@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('ivancarlos', 'Ivan', 'ivancarlos377@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('russelmac', 'Russel', 'russellmac12@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('quinndavid', 'Quinn', 'quinndavid314@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('rachelsam', 'Rachel', 'rachelsam253@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('quinnjack', 'Quinn', 'quinnjack323@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('wendyivan', 'Wendy', 'wendyivan457@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('nathanrachel', 'Nathan', 'nathanrachel404@hotmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('davidtom', 'David', 'davidtom668@yahoo.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('carlosboja', 'Carlos', 'carlosboja514@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('davidolivia', 'David', 'davidolivia447@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', False),
            ('carlosmegan', 'Carlos', 'carlosmegan216@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('carlostom', 'Carlos', 'carlostom87@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('zanedavid', 'Zane', 'zanedavid855@gmail.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('fionatom', 'Fiona', 'fionatom693@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('xanderxander', 'Xander', 'xanderxander175@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('katiejack', 'Katie', 'katiejack723@example.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True),
            ('victoralice', 'Victor', 'victoralice298@test.com', '$2y$10$UliM/tUf0jn/a9HWUjnfBON4.uP/YBMbckFoDZnyNszDf424gbL3u', True);

INSERT INTO post_(content_, description_, likes_, comments_, time_, created_by, content_type) VALUES
            ('imagina uma imagem aqui', '🌍✈️ Exploring the world, one adventure at a time! 🌟 Embracing new cultures, tasting exotic flavors, and creating memories that last a lifetime. 📸 Join me on this journey of discovery and lets inspire each other to wander far and wide. 🌏 #Nature #TravelLife #Wanderlust #AdventureAwaits 🗺️✨', 2, 0, '2023-10-25 08:30:15', 1,'image'),
            ('imagina um video aqui', '🌅 Chase sunsets, collect stories. Where to next? 🗺️✨ #SunsetSeeker #Nature #TravelInspiration', 2, 0, '2023-10-25 08:30:15', 1,'video'),
            ('imagina um video aqui', '🏰 Lost in the magic of historical wonders. Every corner has a tale to tell. 📜 #Travel #HistoryBuff #ExploreDiscoverRepeat', 2, 0, '2023-10-25 08:30:15', 1,'video'),
            ('imagina uma imagem aqui', '🍜 Savoring the flavors of the world, one delicious dish at a time. Whats your favorite global cuisine? 🌮🍣 #DeliciousFood #FoodExplorer #TasteTheWorld', 2, 0, '2023-10-25 08:30:15', 1,'image'),
            ('imagina uma imagem aqui', '🌊 Dive into the unknown, explore beneath the surface. Adventure awaits beneath the waves! 🌊🐠 #UnderwaterExplorer #OceanLove', 2, 0, '2023-10-25 08:30:15', 1,'video'),
            ('imagina uma imagem aqui', '🚂 All aboard the train of discovery! Next stop: Unknown wonders and untold stories. 🚄🌐 #Travel #TrainTravel #JourneyOfATraveler', 2, 0, '2023-10-25 08:30:15', 1,'video'),
            ('imagina um video aqui', '🏞️ Natures masterpiece, where every hike unveils a new canvas. Lace-up and explore! 🥾🌲 #HikingAdventures #NatureLover', 2, 0, '2023-10-25 08:30:15', 1,'image'),
            ('imagina um video aqui', '🌌 Stargazing under foreign skies, connecting with the cosmos. Share your favorite celestial moments! 🌠🔭 #StarryNights #CosmicConnection', 2, 0, '2023-10-25 08:30:15', 1,'image'),
            ('imagina uma imagem aqui', '🏰 Living the fairytale in historic castles. Because every traveler deserves a royal adventure! 👑🏰 #CastleExplorer #FairytaleDreams', 2, 0, '2023-10-25 08:30:15', 1,'video'),
            ('imagina uma imagem aqui', '🏝️ Island vibes and salty breezes. Tag your travel buddy for the next tropical escape! 🌺🏖️ #Nature #IslandLife #BeachBound', 2, 0, '2023-10-25 08:30:15', 1,'image'),
            ('imagina um video aqui', '🎵 Music is my travel companion, harmonizing with every journey. Share your favorite travel playlist below! 🌍🎶 #Nature #TravelTunes #MusicalAdventures', 2, 0, '2023-11-05 10:45:30', 1,'video'),
            ('imagina uma imagem aqui', '🏞️ Exploring the beauty of untouched landscapes. Join me on this quest for hidden gems! 💎 #OffTheBeatenPath #NatureExplorer', 2, 0, '2023-11-07 15:20:45', 1,'image'),

            ('imagina um video aqui', '🚁 Soaring above city skylines, capturing the pulse of urban life from a new perspective. 🏙️✨ #CityscapeAerials #SkyHigh', 2, 0, '2023-11-10 18:10:20', 1,'video'),

            ('imagina uma imagem aqui', '🌸 Embracing the tranquility of botanical gardens, where every bloom tells a story. 🌺📖 #BotanicalBeauty #FloralEscape', 2, 0, '2023-11-15 09:55:10', 1,'image'),

            ('imagina um video aqui', '🏰 Roaming the cobblestone streets of historic towns, discovering tales of the past. Step back in time with me! 🕰️🗺️ #TimeTraveler #HistoricJourneys', 2, 0, '2023-11-18 12:30:55', 1,'video'),

            ('imagina uma imagem aqui', '🌅 Morning rituals with a view – because every sunrise is a promise of a new adventure. ☀️🌄 #Photography #MorningMagic #SunriseChaser', 2, 0, '2023-11-22 07:15:40', 1,'image'),

            ('imagina um video aqui', '🏞️ Chasing waterfalls and feeling the mist on my face. Natures symphony in full play! 🌊🎶 #WaterfallWonders #NatureMelody', 2, 0, '2023-11-25 14:20:30', 1,'video'),

            ('imagina um video aqui', '🌌 Campfire tales under the starlit sky – where stories come to life in the glow of the flames. 🔥✨ #Adventure #CampfireChronicles #NighttimeNarratives', 2, 0, '2023-11-28 20:05:15', 1,'video'),

            ('imagina uma imagem aqui', '🏔️ Conquering mountain peaks and finding serenity at the summit. The higher you climb, the clearer the view! ⛰️🏞️ #MountainExplorer #PeakPerformance', 2, 0, '2023-12-02 11:40:50', 1,'image'),

            ('imagina uma imagem aqui', '🌈 Chasing rainbows in unexpected places – because joy is found in the most colorful moments of life. 🌦️🌈 #RainbowChaser #ColorfulAdventures', 2, 0, '2023-12-05 16:30:25', 1,'image'),
            ('imagina uma imagem aqui', '🗼 Parisian vibes and croissants for breakfast. Exploring the streets of Paris, where every corner whispers romance. 🥐🇫🇷 #ParisianDreams #CityOfLove', 2, 0, '2023-12-10 09:15:30', 30,'image'),

            ('imagina um video aqui', '🍀 Dublins lively spirit captured in every pubs melody. Join me in the heart of Irelands capital! 🎻🍺 #DublinDiaries #IrishAdventures', 2, 0, '2023-12-12 13:40:45', 36,'video'),

            ('imagina um video aqui', '🏯 Tokyos neon lights painting the night. Dive into the energy of the bustling metropolis! 🌃🇯🇵 #TokyoNights #CityLights', 2, 0, '2023-12-15 18:30:20', 30,'video'),

            ('imagina uma imagem aqui', '🇬🇧 London calling! From red phone booths to the Thames, savoring the iconic sights of the British capital. ☎️🎡 #LondonExplorer #BritishCharm', 2, 0, '2023-12-18 11:55:10', 30,'image'),

            ('imagina uma imagem aqui', '🚤 Cruising through the canals of Venice, where every bridge tells a story. Lost in the charm of Italys floating city. 🇮🇹🛶 #VenetianDreams #CanalCruise', 2, 0, '2023-12-20 15:20:55', 32,'image'),

            ('imagina um video aqui', '🍷 Sipping port wine in the historic cellars of Oporto. Embracing the flavors of Portugals enchanting city by the river. 🍇🇵🇹 #PortoWineTasting #RiversideRendezvous', 2, 0, '2023-12-22 20:10:30', 32,'video'),

            ('imagina um video aqui', '🏰 Exploring the charming streets of Budapest, where history meets modernity. Join me on a journey through Hungarys vibrant capital! 🇭🇺🏰 #BudapestAdventures #HistoricCharm', 2, 0, '2023-12-25 14:45:30', 33,'video'),

            ('imagina uma imagem aqui', '🌆 Budapests skyline illuminated at twilight. Capturing the citys beauty from the heights! 🌇🏞️ #BudapestSkyline #CityPanorama', 2, 0, '2023-12-28 20:25:15', 34,'image'),

            ('imagina uma imagem aqui', '🏞️ Serenity found in the gardens of Kyoto, a peaceful escape in the heart of Japan. 🌸🍵 #KyotoGardens #JapaneseTranquility', 2, 0, '2024-01-02 11:10:50', 35,'image'),

            ('imagina um video aqui', '🌉 Strolling along the illuminated bridges of Prague, where every step feels like a journey through time. 🌌🇨🇿 #PragueNights #CityTimeWarp', 2, 0, '2024-01-05 16:50:25', 36,'video');

INSERT INTO group_(name_, description_) VALUES
            ('Mundo Fora do Mapa', ''),
            ('Aventura sem Limites', ''),
            ('ADOROVIAJAR!ADOROVIAJAR!', 'ADOROVIAJAR!ADOROVIAJAR!ADOROVIAJAR!ADOROVIAJAR!ADOROVIAJAR!ADOROVIAJAR!ADOROVIAJAR!ADOROVIAJAR!'),
            ('Rota dos Sonhos', ''),
            ('Festival2031', 'Vens? S/N'),
            ('Roteiro Aventura', '');
            
INSERT INTO post_(content_, description_, likes_, comments_, time_, created_by, groupID, content_type) VALUES
            ('imagina uma imagem aqui', 'linda paisagem', 3, 0, '2023-10-25 08:30:15', 1, 3,'image'),
            ('imagina um video aqui', 'boa jogada', 4, 0, '2023-10-25 08:30:15', 1, 3,'video'),
            ('imagina um video aqui', 'dia de folga', 4, 0, '2023-10-25 08:30:15', 1, 3,'video'),
            ('imagina uma imagem aqui', 'a próxima tentativa será melhor', 4, 0, '2023-10-25 08:30:15', 1, NULL,'image'),
            ('imagina uma imagem aqui', 'boa escolha', 4, 0, '2023-10-25 08:30:15', 1, NULL,'video'),
            ('imagina uma imagem aqui', 'experiência única', 4, 0, '2023-10-25 08:30:15', 1, nULL,'video'),
            ('imagina um video aqui', 'vou repetir', 4, 0, '2023-10-25 08:30:15', 1, NULL,'image'),
            ('imagina um video aqui', 'não gostei', 4, 0, '2023-10-25 08:30:15', 1, NULL,'image'),
            ('imagina uma imagem aqui', 'adorei este passeio', 4, 0, '2023-10-25 08:30:15', 1, NULL,'video'),
            ('imagina uma imagem aqui', 'recomendo muito este restaurante', 4, 0, '2023-10-25 08:30:15', 1, NULL,'image');

INSERT INTO message_(description_, time_, sender, receiver, sent_to, message_replies) VALUES
            ('ADORO VIAJAR!!!!', '2023-10-25 08:30:15', 1, NULL, 3, NULL),
            ('ADORO VIAJAR!!!!', '2023-10-25 08:31:00', 5, NULL, 3, NULL),
            ('ADORO VIAJAR!!!!', '2023-10-25 08:32:00', 48, NULL, 3, NULL),
            ('ADORO VIAJAR!!!!', '2023-10-25 08:33:00', 31, NULL, 3, NULL),
            ('ADORO VIAJAR!!!!', '2023-10-25 08:34:00', 22, NULL, 3, NULL),
            ('ADORO VIAJAR!!!!', '2023-10-25 08:35:00', 23, NULL, 3, NULL),
            ('ADORO VIAJAR!!!!', '2023-10-25 08:36:00', 67, NULL, 3, NULL),
            ('Olá, espero que gostes do Travly!', '2023-10-25 08:31:00', 1, 48, NULL, NULL),
            ('Olá, espero que gostes do Travly!', '2023-10-25 08:31:00', 1, 23, NULL, NULL),
            ('Olá, espero que gostes do Travly!', '2023-10-25 08:31:00', 1, 67, NULL, NULL),
            ('Olá, espero que gostes do Travly!', '2023-10-25 08:31:00', 1, 31, NULL, NULL),
            ('Olá, espero que gostes do Travly!', '2023-10-25 08:31:00', 1, 5, NULL, NULL),
            ('Por acaso até estou :)', '2023-10-25 08:35:00', 67, 1, NULL, 10);

INSERT INTO comment_(description_, likes_, time_, id, postID, comment_replies) VALUES
            ('Ganda post chavalo!', 0, '2023-10-25 08:31:00', 34, 1, NULL),
            ('Brigadão sócio.', 0, '2023-10-25 08:32:00', 1, 1, 1),
            ('Nunca vi nada assim!', 0, '2023-10-25 07:31:00', 67, 2, NULL),
            ('Uau!!!', 0, '2023-10-25 08:31:00', 45, 2, NULL);

INSERT INTO notification_(notificationid, description_, time_, seen, notifies, sends_notif) VALUES
            (1, 'SarahWilson accepted your follow request!', '2023-10-25 15:55:10', FALSE, 1, 5),
            (2, 'JohnDoe started following you!', '2023-10-25 08:30:15', FALSE, 5, 3),
            (3, 'AliceSmith liked one of your posts!', '2023-10-25 10:15:40', FALSE, 1, 4),
            (4, 'RobertJohnson sent you a follow request!', '2023-10-25 12:20:55', FALSE, 1, 6),
            (5, 'EmilyBrown left a comment in one of your posts!', '2023-10-25 14:45:30', FALSE, 1, 9),
            (6, 'DanielRoberts liked one of your posts!', '2023-10-25 16:40:25', FALSE, 6, 2),
            (7, 'SophiaGarcia sent you a follow request!', '2023-10-25 17:25:55', FALSE, 3, 7),
            (8, 'MichaelAnderson started following you!', '2023-10-25 18:10:45', FALSE, 6, 8),
            (9, 'AlexaHall liked one of your posts!', '2023-10-25 19:30:20', FALSE, 1, 2),
            (10, 'OliverSmith left a comment in one of your posts!', '2023-10-25 20:15:35', FALSE, 1, 5);
            /*
            (11, 'You accepted LilyBrown follow request', '2023-10-25 21:05:50', FALSE, 12, 10),
            (12, 'LucasJones requested to follow you', '2023-10-25 22:40:10', FALSE, 9, 1),
            (13, 'EllaDavis started following your fashion page', '2023-10-25 23:30:30', FALSE, 7, 4),
            (14, 'GraceTurner liked your new artwork', '2023-10-26 08:00:15', FALSE, 13, 6),
            (15, 'NoahMartinez commented on your tech review', '2023-10-26 10:45:25', FALSE, 14, 3),
            (16, 'CharlotteWhite requested to follow you', '2023-10-26 12:20:50', FALSE, 1, 5),
            (17, 'You accepted JamesJohnson follow request', '2023-10-26 14:35:40', FALSE, 7, 9),
            (18, 'WilliamSmith liked your gardening tips', '2023-10-26 15:50:10', FALSE, 4, 8),
            (19, 'AvaTaylor commented on your fitness post', '2023-10-26 16:30:30', FALSE, 2, 11),
            (20, 'SophiaHarris started following your photography account', '2023-10-26 17:15:55', FALSE, 9, 1),
            (21, 'BenjaminWilson requested to follow you', '2023-10-26 18:25:15', FALSE, 12, 2),
            (22, 'EthanAnderson liked your recent artwork', '2023-10-26 19:40:40', FALSE, 10, 3),
            (23, 'You accepted MiaGarcia follow request', '2023-10-26 20:50:25', FALSE, 4, 7),
            (24, 'LucyDavis commented on your travel blog', '2023-10-26 21:35:10', FALSE, 3, 8),
            (25, 'DavidClark started following your fitness page', '2023-10-26 22:20:35', FALSE, 15, 6),
            (26, 'EmmaMartinez liked your food photography', '2023-10-27 08:10:20', FALSE, 14, 5),
            (27, 'LoganSmith requested to follow you', '2023-10-27 10:40:45', FALSE, 11, 6),
            (28, 'You accepted GraceRoberts follow request', '2023-10-27 12:55:30', FALSE, 2, 7),
            (29, 'JackTurner liked your new fashion collection', '2023-10-27 14:30:15', FALSE, 4, 9),
            (30, 'SophieJohnson commented on your art exhibit', '2023-10-27 15:20:25', FALSE, 3, 1),
            (31, 'You accepted DanielWilson follow request', '2023-10-27 16:15:50', FALSE, 5, 8),
            (32, 'LiamHarris started following your travel blog', '2023-10-27 17:45:55', FALSE, 6, 10),
            (33, 'ZoeSmith requested to follow you', '2023-10-27 18:30:40', FALSE, 7, 12),
            (34, 'You accepted WilliamDavis follow request', '2023-10-27 19:55:30', FALSE, 1, 13),
            (35, 'OliviaTaylor liked your latest tech review', '2023-10-27 21:40:15', FALSE, 15, 2),
            (36, 'JacksonTurner commented on your gardening post', '2023-10-27 22:25:55', FALSE, 8, 14),
            (37, 'IsabellaHarris started following your cooking channel', '2023-10-28 08:05:25', FALSE, 10, 3),
            (38, 'MasonAnderson liked your music composition', '2023-10-28 10:45:10', FALSE, 7, 6),
            (39, 'You accepted MiaJones follow request', '2023-10-28 12:30:35', FALSE, 4, 5),
            (40, 'EvelynGarcia commented on your latest blog post', '2023-10-28 14:50:50', FALSE, 2, 9);
            */

INSERT INTO admin_(id) VALUES
            (1);

INSERT INTO belongs_(id, groupID) VALUES 
            (23, 1),
            (7,2),
            (1,3),
            (15,4),
            (31,5),
            (9,6),
            (3, 1), 
            (4, 2), 
            (5, 3), 
            (1, 4), 
            (8, 5), 
            (10, 6), 
            (12, 1), 
            (13, 2), 
            (14, 3), 
            (16, 4), 
            (17, 5), 
            (18, 6), 
            (20, 1), 
            (1, 2), 
            (22, 3), 
            (24, 4), 
            (25, 5), 
            (26, 6), 
            (28, 1), 
            (29, 2), 
            (30, 3), 
            (32, 4), 
            (33, 5), 
            (34, 6), 
            (36, 1), 
            (37, 2), 
            (38, 3), 
            (39, 4), 
            (40, 5), 
            (1, 6), 
            (42, 1), 
            (43, 2), 
            (44, 3), 
            (45, 4), 
            (46, 5), 
            (47, 6), 
            (49, 1), 
            (50, 2), 
            (51, 3), 
            (52, 4), 
            (53, 5), 
            (54, 6), 
            (56, 1), 
            (57, 2), 
            (58, 3), 
            (59, 3), 
            (60, 4), 
            (61, 5), 
            (62, 6), 
            (64, 1), 
            (65, 2), 
            (66, 3), 
            (67, 4), 
            (68, 5), 
            (69, 6), 
            (71, 2), 
            (72, 3), 
            (73, 4), 
            (74, 5), 
            (75, 6), 
            (76, 1), 
            (77, 1), 
            (78, 2), 
            (79, 3), 
            (80, 4), 
            (81, 5), 
            (82, 6), 
            (83, 1);

INSERT INTO owner_(id, groupID) VALUES
            (23, 1),
            (7,2),
            (1,3),
            (15,4),
            (31,5),
            (9,6);

INSERT INTO user_notification(notificationid, id, notification_type) VALUES
            (1, 5, 'accepted_follow'),
            (2, 3, 'started_following'),
            (4, 6, 'request_follow'),
            (7, 7, 'request_follow'),
            (8, 8, 'started_following');

            /*
            (1, 3, 'request_follow'),
            (2, 5, 'accepted_follow'),
            (3, 8, 'started_following'),
            (4, 2, 'request_follow'),
            (5, 6, 'accepted_follow'),
            (6, 1, 'started_following'),
            (7, 7, 'accepted_follow'),
            (8, 4, 'request_follow'),
            (9, 10, 'accepted_follow'),
            (10, 9, 'started_following'),
            (11, 5, 'request_follow'),
            (12, 2, 'accepted_follow'),
            (13, 3, 'started_following'),
            (14, 6, 'request_follow'),
            (15, 8, 'started_following'),
            (16, 7, 'accepted_follow'),
            (17, 1, 'started_following'),
            (18, 4, 'request_follow'),
            (19, 10, 'accepted_follow'),
            (20, 9, 'started_following'),
            (21, 5, 'request_follow'),
            (22, 2, 'accepted_follow'),
            (23, 3, 'started_following'),
            (24, 6, 'request_follow'),
            (25, 8, 'started_following'),
            (26, 1, 'accepted_follow'),
            (27, 4, 'request_follow'),
            (28, 10, 'started_following'),
            (29, 9, 'request_follow'),
            (30, 5, 'accepted_follow'),
            (31, 2, 'started_following'),
            (32, 6, 'request_follow'),
            (33, 1, 'accepted_follow'),
            (34, 8, 'started_following'),
            (35, 3, 'request_follow'),
            (36, 7, 'accepted_follow'),
            (37, 4, 'started_following'),
            (38, 10, 'request_follow'),
            (39, 9, 'accepted_follow'),
            (40, 5, 'started_following');
            */

INSERT INTO post_notification(notificationid, postID, notification_type) VALUES
            (3, 1,'liked_post'),
            (5, 1,'commented_post'),
            (6, 1,'liked_post'),
            (9, 1,'liked_post'),
            (10, 1,'commented_post');
            /*
            (1, 3, 'liked_post'),
            (2, 5, 'liked_post'),
            (3, 8, 'commented_post'),
            (4, 2, 'liked_post'),
            (5, 6, 'commented_post'),
            (6, 1, 'liked_post'),
            (7, 7, 'commented_post'),
            (8, 4, 'liked_post'),
            (9, 10, 'commented_post'),
            (10, 9, 'liked_post'),
            (11, 5, 'liked_post'),
            (12, 2, 'commented_post'),
            (13, 3, 'liked_post'),
            (14, 6, 'commented_post'),
            (15, 8, 'liked_post'),
            (16, 7, 'commented_post'),
            (17, 1, 'liked_post'),
            (18, 4, 'commented_post'),
            (19, 10, 'liked_post'),
            (20, 9, 'commented_post'),
            (21, 5, 'liked_post'),
            (22, 2, 'liked_post'),
            (23, 3, 'commented_post'),
            (24, 6, 'liked_post'),
            (25, 8, 'commented_post'),
            (26, 1, 'liked_post'),
            (27, 4, 'liked_post'),
            (28, 10, 'commented_post'),
            (29, 9, 'liked_post'),
            (30, 5, 'commented_post'),
            (31, 2, 'liked_post'),
            (32, 6, 'liked_post'),
            (33, 1, 'commented_post'),
            (34, 8, 'liked_post'),
            (35, 3, 'commented_post'),
            (36, 7, 'liked_post'),
            (37, 4, 'commented_post'),
            (38, 10, 'liked_post'),
            (39, 9, 'commented_post'),
            (40, 5, 'liked_post');
            */

INSERT INTO request_(senderid, receiverid) VALUES
            (6, 1);
            /*
            (6, 17),
            (34, 48),
            (71, 32),
            (54, 19),
            (81, 2),
            (4, 90),
            (12, 85),
            (63, 25),
            (93, 7),
            (9, 77),
            (31, 44),
            (16, 73),
            (41, 55),
            (38, 67),
            (90, 14),
            (46, 26),
            (84, 65),
            (3, 50),
            (10, 86),
            (20, 59),
            (23, 47),
            (29, 53),
            (66, 78),
            (58, 68),
            (30, 22),
            (37, 91),
            (94, 8),
            (61, 42),
            (75, 15),
            (52, 36),
            (1, 80),
            (28, 64),
            (11, 27),
            (51, 79),
            (35, 33),
            (72, 5),
            (88, 76),
            (24, 74),
            (62, 70),
            (45, 94),
            (40, 69),
            (43, 49),
            (39, 57),
            (82, 60),
            (89, 21),
            (56, 87),
            (64, 18),
            (82, 37),
            (8, 33),
            (57, 76),
            (13, 83),
            (70, 4),
            (91, 26),
            (6, 49),
            (17, 75),
            (88, 45),
            (29, 55),
            (2, 23),
            (48, 61),
            (38, 58),
            (30, 9),
            (94, 47),
            (41, 25),
            (31, 81),
            (15, 68),
            (59, 86),
            (79, 22),
            (32, 66),
            (53, 44),
            (72, 85),
            (42, 77),
            (7, 20),
            (12, 73),
            (27, 35),
            (67, 3),
            (54, 80),
            (50, 34),
            (60, 40),
            (43, 10),
            (92, 19),
            (78, 56),
            (71, 63),
            (46, 24),
            (1, 84),
            (74, 28),
            (5, 69),
            (36, 94);*/


INSERT INTO follows_(followerid, followedid) VALUES
            (23, 67),
            (10, 35),
            (5, 51),
            (3, 70),
            (7, 37),
            (59, 80),
            (13, 25),
            (18, 58),
            (4, 55),
            (22, 72),
            (45, 76),
            (44, 71),
            (11, 34),
            (12, 43),
            (30, 50),
            (21, 77),
            (46, 60),
            (42, 52),
            (41, 48),
            (31, 63),
            (38, 74),
            (53, 65),
            (24, 33),
            (33, 22),
            (75, 32),
            (8, 36),
            (64, 29),
            (77, 68),
            (48, 27),
            (35, 57),
            (61, 79),
            (14, 70),
            (16, 53),
            (67, 8),
            (66, 62),
            (54, 3),
            (15, 17),
            (25, 2),
            (1, 18),
            (51, 19),
            (49, 14),
            (20, 45),
            (19, 73),
            (76, 64),
            (26, 78),
            (58, 40),
            (39, 69),
            (27, 12),
            (73, 47),
            (28, 56),
            (6, 9),
            (57, 41),
            (47, 85),
            (32, 5),
            (69, 1),
            (52, 83),
            (43, 59),
            (37, 84),
            (70, 81),
            (56, 30),
            (36, 16),
            (68, 66),
            (50, 26),
            (40, 15),
            (62, 82),
            (17, 28),
            (34, 38),
            (31, 46),
            (2, 7),
            (29, 54),
            (19, 21),
            (9, 31),
            (60, 67),
            (55, 24),
            (68, 7),
            (20, 74),
            (83, 79),
            (54, 50),
            (67, 45),
            (31, 62),
            (3, 2),
            (61, 48),
            (66, 73),
            (84, 34),
            (21, 33),
            (18, 63),
            (13, 80),
            (72, 39),
            (81, 86),
            (22, 37),
            (74, 69),
            (30, 78),
            (5, 13),
            (38, 61),
            (80, 50),
            (66, 43),
            (7, 58),
            (51, 71),
            (23, 49),
            (62, 10),
            (39, 12),
            (14, 25),
            (28, 36),
            (42, 72),
            (21, 63),
            (31, 11),
            (35, 33),
            (15, 46),
            (55, 8),
            (19, 68),
            (45, 32),
            (52, 67),
            (6, 44),
            (47, 16),
            (73, 64),
            (26, 74),
            (60, 20),
            (34, 1),
            (18, 24),
            (4, 70),
            (56, 41),
            (69, 76),
            (22, 75),
            (59, 3),
            (48, 17),
            (37, 29),
            (27, 40),
            (53, 2),
            (9, 57),
            (65, 79),
            (77, 81),
            (82, 86),
            (83, 84),
            (85, 82),
            (86, 6),
            (84, 59),
            (81, 14),
            (80, 19),
            (79, 66),
            (78, 30),
            (76, 46),
            (75, 23),
            (74, 53),
            (72, 49),
            (71, 51),
            (70, 62),
            (68, 13),
            (67, 37),
            (64, 24),
            (63, 73),
            (61, 28),
            (58, 22),
            (57, 35),
            (56, 31),
            (54, 45),
            (47, 29),
            (44, 27),
            (42, 18),
            (41, 40),
            (39, 38),
            (36, 34),
            (33, 32),
            (5, 4),
            (2, 1);
            

INSERT INTO post_likes(id, postID) VALUES
    (2, 1),
    (23, 1),
    (20, 2),
    (12, 2),
    (32, 3),
    (54, 3),
    (76, 4),
    (43, 4),
    (3, 5),
    (25, 5),
    (24, 6),
    (23, 6),
    (47, 7),
    (46, 7),
    (23, 8),
    (75, 8),
    (23, 9),
    (13, 9),
    (64, 10),
    (55, 10),
    (45, 11),
    (23, 11),
    (65, 12),
    (76, 12),
    (2, 13),
    (23, 13),
    (20, 14),
    (12, 14),
    (32, 15),
    (54, 15),
    (76, 16),
    (43, 17),
    (3, 18),
    (25, 16),
    (24, 17),
    (23, 18),
    (47, 19),
    (46, 19),
    (23, 20),
    (75, 20),
    (23, 21),
    (13, 21),
    (64, 22),
    (55, 22),
    (45, 23),
    (23, 23),
    (65, 24),
    (76, 24),
    (47, 25),
    (46, 25),
    (23, 26),
    (75, 26),
    (23, 27),
    (13, 27),
    (64, 28),
    (55, 28),
    (45, 29),
    (23, 29),
    (65, 30),
    (76, 30);
    
INSERT INTO comment_likes(id, commentID) VALUES
            (18, 2),
            (12, 2), 
            (13, 2),
            (93, 2),
            (94, 2);

INSERT INTO saved_post(id, postID) VALUES
            (3, 1),
            (56, 1),
            (38, 1),
            (23, 2),
            (2, 2), 
            (49, 2),
            (36, 2);