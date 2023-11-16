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
    userID SERIAL PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    name_ TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_ TEXT NOT NULL,
    private_ BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE post_ (
    postID SERIAL PRIMARY KEY,
    content TEXT NOT NULL, 
    description_ TEXT,
    likes_ INTEGER DEFAULT 0,
    comments INTEGER DEFAULT 0, 
    time_ INTEGER NOT NULL,
    created_by INTEGER NOT NULL REFERENCES user_ (userID) ON UPDATE CASCADE,
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
    time_ INTEGER NOT NULL, 
    sender INTEGER NOT NULL REFERENCES user_ (userID) ON UPDATE CASCADE, 
    receiver INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE, 
    sent_to INTEGER REFERENCES group_ (groupID) ON UPDATE CASCADE, 
    message_replies INTEGER DEFAULT NULL REFERENCES message_ (messageID) ON UPDATE CASCADE          -- tirar default null
);

CREATE TABLE comment_ (
    commentID SERIAL PRIMARY KEY, 
    description_ TEXT NOT NULL,
    likes_ INTEGER DEFAULT 0, 
    time_ INTEGER NOT NULL,
    userID INTEGER NOT NULL REFERENCES user_ (userID) ON UPDATE CASCADE,
    postID INTEGER NOT NULL REFERENCES post_ (postID) ON UPDATE CASCADE,
    comment_replies INTEGER DEFAULT NULL REFERENCES comment_ (commentID) ON UPDATE CASCADE          -- tirar default null
);

CREATE TABLE notification_ (
    notificationID SERIAL PRIMARY KEY,
    description_ TEXT NOT NULL, 
    time_ INTEGER NOT NULL,
    notifies INTEGER NOT NULL REFERENCES user_ (userID),
    sends_notif INTEGER NOT NULL REFERENCES user_ (userID)
);

CREATE TABLE admin_ (
    userID SERIAL PRIMARY KEY REFERENCES user_ (userID) ON UPDATE CASCADE
);

CREATE TABLE owner_ (
    userID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    groupID INTEGER REFERENCES group_ (groupID) ON UPDATE CASCADE,
    PRIMARY KEY (userID, groupID)
);

CREATE TABLE belongs_ (
    userID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    groupID INTEGER REFERENCES group_ (groupID) ON UPDATE CASCADE,
    PRIMARY KEY (userID, groupID)
);

CREATE TABLE user_notification (
    notificationID INTEGER PRIMARY KEY REFERENCES notification_ (notificationID) ON UPDATE CASCADE,
    userID INTEGER NOT NULL REFERENCES user_ (userID), 
    notification_type user_notification_types NOT NULL
);

CREATE TABLE post_notification (
    notificationID INTEGER PRIMARY KEY REFERENCES notification_ (notificationID) ON UPDATE CASCADE,
    postID INTEGER NOT NULL REFERENCES post_ (postID) ON UPDATE CASCADE, 
    notification_type post_notification_types NOT NULL
);

CREATE TABLE request_ (
    senderID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    receiverID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    PRIMARY KEY (senderID, receiverID)
);

CREATE TABLE follows_ (
    followerID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    followedID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    PRIMARY KEY (followerID, followedID)
);

CREATE TABLE post_likes (
    userID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    postID INTEGER REFERENCES post_ (postID) ON UPDATE CASCADE,
    PRIMARY KEY (userID, postID)
);

CREATE TABLE comment_likes (
    userID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    commentID INTEGER REFERENCES comment_ (commentID) ON UPDATE CASCADE,
    PRIMARY KEY (userID, commentID)
);

CREATE TABLE saved_post (
    userID INTEGER REFERENCES user_ (userID) ON UPDATE CASCADE,
    postID INTEGER REFERENCES post_ (postID) ON UPDATE CASCADE,
    PRIMARY KEY (userID, postID)
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
         setweight(to_tsvector('english', NEW.name), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.username <> OLD.username OR NEW.name <> OLD.name) THEN
           NEW.user_tsvectors = (
             setweight(to_tsvector('english', NEW.username), 'A') ||
             setweight(to_tsvector('english', NEW.name), 'B')
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
         setweight(to_tsvector('english', NEW.name), 'A') ||
         setweight(to_tsvector('english', NEW.description), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
           NEW.group_tsvectors = (
             setweight(to_tsvector('english', NEW.name), 'A') ||
             setweight(to_tsvector('english', NEW.description), 'B')
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
         setweight(to_tsvector('english', NEW.content), 'A') ||
         setweight(to_tsvector('english', NEW.description), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.content <> OLD.content OR NEW.description <> OLD.description) THEN
           NEW.post_tsvectors = (
             setweight(to_tsvector('english', NEW.content), 'A') ||
             setweight(to_tsvector('english', NEW.description), 'B')
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
        NEW.message_tsvectors = setweight(to_tsvector('english', NEW.description), 'A');
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.description <> OLD.description) THEN
           NEW.message_tsvectors = setweight(to_tsvector('english', NEW.description), 'A');
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
        NEW.comment_tsvectors = setweight(to_tsvector('english', NEW.description), 'A');
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.description <> OLD.description) THEN
           NEW.comment_tsvectors = setweight(to_tsvector('english', NEW.description), 'A');
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
    IF EXISTS (SELECT * FROM post_ WHERE NEW.postID = post_.postID AND NEW.userID = post_.created_by) 
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
    IF EXISTS (SELECT * FROM comment_ WHERE NEW.commentID = comment_.commentID AND NEW.userID = comment_.userID) 
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
    IF EXISTS (SELECT * FROM post_likes WHERE NEW.userID = post_likes.userID AND NEW.postID = post_likes.postID) 
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
    IF EXISTS (SELECT * FROM comment_likes WHERE NEW.userID = userID AND NEW.commentID = commentID) 
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
    IF EXISTS (SELECT * FROM user_, post_ WHERE NEW.postID = post_.postID AND post_.created_by = user_userID AND user_.private_ )
        AND NOT EXISTS (SELECT * FROM post_,follows_ WHERE NEW.postID = post_.postID AND NEW.userID = follows_.followerID AND follows_.followedID = post_.created_by) 
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
    IF NOT EXISTS ( SELECT  * FROM belongs_ WHERE NEW.userID = belongs_.userID AND NEW.groupID = belongs_.groupID) 
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
    IF EXISTS (SELECT * FROM follows_ WHERE NEW.senderID = follows_.followerID AND NEW.receiverID = follows_.followedID)
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
    IF NEW.senderID = NEW.receiverID 
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
    BEFORE INSERT ON post_
    FOR EACH ROW
    EXECUTE PROCEDURE delete_post_action();


--TRIGGER11
--When deleting a comment it also deletes its likes, subcomments and notifications.

CREATE FUNCTION delete_comment_action() RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM comment_likes WHERE OLD.commentID = comment_likes.commentID;
    DELETE FROM post_notification WHERE OLD.commentID = post_notification.commentID;
    DELETE FROM comment_ WHERE OLD.commentID = comment_.commentID;

    RETURN OLD;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER delete_comment_action
    BEFORE INSERT ON comment_
    FOR EACH ROW
    EXECUTE PROCEDURE delete_comment_action();


--====================================--
--            Transactions            --
--====================================--


-- | SQL Reference   | Delete user                    |
-- | --------------- | ----------------------------------- |
-- | Justification   | The isolation level is set to Repeatable Read to prevent any potential updates to the notification table caused by a deletion in various tables carried out by a concurrent transaction. This choice is made to ensure data consistency and avoid the storage of inconsistent data.  |
-- | Isolation level | REPEATABLE READ |
-- | `SQL Code`                                   |↓↓↓↓↓↓↓↓↓↓↓|


SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

BEGIN;
    DELETE FROM user_notification
    WHERE userID = 'ID_do_utilizador';

    DELETE FROM post_notification
    WHERE postID IN (SELECT postID FROM post_ WHERE created_by = 'ID_do_utilizador');

    DELETE FROM request_
    WHERE senderID = 'ID_do_utilizador' OR receiverID = 'ID_do_utilizador';

    DELETE FROM follows_
    WHERE followerID = 'ID_do_utilizador' OR followedID = 'ID_do_utilizador';

    DELETE FROM post_likes
    WHERE userID = 'ID_do_utilizador';

    DELETE FROM comment_likes
    WHERE userID = 'ID_do_utilizador';

    DELETE FROM comment_
    WHERE userID = 'ID_do_utilizador';

    DELETE FROM message_
    WHERE sender = 'ID_do_utilizador';

    UPDATE message_
    SET receiver = NULL
    WHERE receiver = 'ID_do_utilizador';

    DELETE FROM belongs_
    WHERE userID = 'ID_do_utilizador';

    DELETE FROM owner_
    WHERE userID = 'ID_do_utilizador';

    DELETE FROM group_
    WHERE ownerID = 'ID_do_utilizador';

    DELETE FROM user_
    WHERE userID = 'ID_do_utilizador';
COMMIT;


-- | SQL Reference             | Create New Group                                           |
-- | ------------------------- | ---------------------------------------------------------- |
-- | Justification        | The Repeatable Read isolation level is employed to maintain data consistency during the creation of a new group, preventing any concurrent updates to the notification and group tables. |
-- | Isolation level      | REPEATABLE READ                                            |
-- | SQL Code             |          |

BEGIN;
    INSERT INTO group_ (name_, description_)
    VALUES ('Novo Nome do Grupo', 'Descrição do Grupo');

    DECLARE @newGroupID INT;
    SET @newGroupID = SCOPE_IDENTITY();

    INSERT INTO owner_ (userID, groupID)
    VALUES (<owner_user_id>, @newGroupID);

    INSERT INTO belongs_ (userID, groupID)
    VALUES (<user_id>, @newGroupID);
COMMIT;


-- | SQL Reference   | New comment notification                    |
-- | --------------- | ----------------------------------- |
-- | Justification   | The choice of the Repeatable Read isolation level is essential because without it, there is a risk of an update occurring in the notification table due to an insert operation in the post_notification table committed by a concurrent transaction. This would lead to the storage of inconsistent data.  |
-- | Isolation level | REPEATABLE READ |
-- | `SQL Code`                                   |↓↓↓↓↓↓↓↓↓↓↓|


BEGIN;
    INSERT INTO notification_ (description_, time, notifies, sends_notif)
    VALUES ('Nova notificação de comentário em post', NOW(), (SELECT userID FROM user_ WHERE username = 'username_do_proprietario_do_post'), (SELECT userID FROM user_ WHERE username = 'username_do_utilizador_autenticado'));

    DECLARE newNotificationID INT;
    SET newNotificationID = LAST_INSERT_ID();

    INSERT INTO comment_ (notificationID, postID, description_, likes, time, comment_replies)
    VALUES (newNotificationID, 'ID_do_post', 'Descrição do comentário', 0, NOW(), NULL);
COMMIT;


-- | SQL Reference   | Like post notification                    |
-- | --------------- | ----------------------------------- |
-- | Justification   | The selection of the Repeatable Read isolation level is imperative to mitigate the risk of an update occurring in the notification table due to an insert operation in the post_notification table committed by a concurrent transaction. This safeguards data consistency and prevents the storage of inconsistent information.  |
-- | Isolation level | REPEATABLE READ |
-- | `SQL Code`                                   |↓↓↓↓↓↓↓↓↓↓↓|


SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

IF EXISTS (SELECT 1 FROM post_likes WHERE userID = 'ID_do_utilizador_autenticado' AND postID = 'ID_do_post') THEN
    RETURN;
END IF;

INSERT INTO post_likes (userID, postID)
VALUES ('ID_do_utilizador_autenticado', 'ID_do_post');

IF 'ID_do_utilizador_autenticado' = 'ID_do_proprietario_do_post' THEN
    RETURN;
END IF;

BEGIN;
    INSERT INTO notification_ (description_, time, notifies, sends_notif)
    VALUES ('Nova notificação de "like" em post', NOW(), 'ID_do_proprietario_do_post', 'ID_do_utilizador_autenticado');

    SELECT notificationID
    FROM notification_
    WHERE notifies = 'ID_do_proprietario_do_post' 
    AND sends_notif = 'ID_do_utilizador_autenticado'
    ORDER BY time DESC
    LIMIT 1;

    INSERT INTO post_notification (notificationID, postID, notification_type)
    VALUES ('ID_da_notificacao_obtido_anteriormente', 'ID_do_post', 'liked_post');
COMMIT;


-- | SQL Reference   | Follow notification                    |
-- | --------------- | ----------------------------------- |
-- | Justification   | The adoption of the Repeatable Read isolation level is necessary to prevent the occurrence of an update in the notification table, which could result from an insert operation in the user_notification table committed by a concurrent transaction. This measure ensures the maintenance of data consistency and avoids the storage of inconsistent information.  |
-- | Isolation level | REPEATABLE READ |
-- | `SQL Code`                                   |↓↓↓↓↓↓↓↓↓↓↓|



SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

BEGIN;
    INSERT INTO follows_ (followerID, followedID)
    VALUES ('ID_do_utilizador_autenticado', 'ID_do_utilizador_seguido');

    INSERT INTO notification_ (description_, time, notifies, sends_notif)
    VALUES ('Nova notificação de seguidor', NOW(), 'ID_do_utilizador_seguido', 'ID_do_utilizador_autenticado');

    SELECT notificationID
    FROM notification_
    WHERE notifies = 'ID_do_utilizador_seguido'
    AND sends_notif = 'ID_do_utilizador_autenticado'
    ORDER BY time DESC
    LIMIT 1;

    INSERT INTO user_notification (notificationID, notification_type)
    VALUES ('ID_da_notificacao_obtida_anteriormente', 'started_following');
COMMIT;

