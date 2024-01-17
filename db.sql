/*
 Navicat Premium Data Transfer

 Source Server         : localhost_5433
 Source Server Type    : PostgreSQL
 Source Server Version : 160001 (160001)
 Source Host           : localhost:5433
 Source Catalog        : wdpai
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 160001 (160001)
 File Encoding         : 65001

 Date: 17/01/2024 15:59:17
*/


-- ----------------------------
-- Sequence structure for changelog_eventID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."changelog_eventID_seq";
CREATE SEQUENCE "public"."changelog_eventID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for changelog_eventTypeID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."changelog_eventTypeID_seq";
CREATE SEQUENCE "public"."changelog_eventTypeID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for changelog_groupID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."changelog_groupID_seq";
CREATE SEQUENCE "public"."changelog_groupID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for changelog_initiator_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."changelog_initiator_seq";
CREATE SEQUENCE "public"."changelog_initiator_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for changelog_targetObjectID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."changelog_targetObjectID_seq";
CREATE SEQUENCE "public"."changelog_targetObjectID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for event_types_eventTypeID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."event_types_eventTypeID_seq";
CREATE SEQUENCE "public"."event_types_eventTypeID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for group_members_groupID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."group_members_groupID_seq";
CREATE SEQUENCE "public"."group_members_groupID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for group_members_roleID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."group_members_roleID_seq";
CREATE SEQUENCE "public"."group_members_roleID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for group_members_userID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."group_members_userID_seq";
CREATE SEQUENCE "public"."group_members_userID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for groups_groupID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."groups_groupID_seq";
CREATE SEQUENCE "public"."groups_groupID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for notes_noteID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."notes_noteID_seq";
CREATE SEQUENCE "public"."notes_noteID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for objects_creator_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."objects_creator_seq";
CREATE SEQUENCE "public"."objects_creator_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for objects_groupID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."objects_groupID_seq";
CREATE SEQUENCE "public"."objects_groupID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for objects_objectID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."objects_objectID_seq";
CREATE SEQUENCE "public"."objects_objectID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sessions_userID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."sessions_userID_seq";
CREATE SEQUENCE "public"."sessions_userID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for tasks_taskID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."tasks_taskID_seq";
CREATE SEQUENCE "public"."tasks_taskID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for users_userID_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."users_userID_seq";
CREATE SEQUENCE "public"."users_userID_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Table structure for changelog
-- ----------------------------
DROP TABLE IF EXISTS "public"."changelog";
CREATE TABLE "public"."changelog" (
  "eventID" int4 NOT NULL DEFAULT nextval('"changelog_eventID_seq"'::regclass),
  "initiator" int4 NOT NULL DEFAULT nextval('changelog_initiator_seq'::regclass),
  "groupID" int4 NOT NULL DEFAULT nextval('"changelog_groupID_seq"'::regclass),
  "eventTypeID" int4 NOT NULL DEFAULT nextval('"changelog_eventTypeID_seq"'::regclass),
  "targetObjectID" int4 NOT NULL DEFAULT nextval('"changelog_targetObjectID_seq"'::regclass),
  "when" timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of changelog
-- ----------------------------
INSERT INTO "public"."changelog" VALUES (1, 1, 1, 1, 1, '2024-01-17 14:28:38.418745');
INSERT INTO "public"."changelog" VALUES (2, 1, 1, 1, 2, '2024-01-17 14:29:00.353171');
INSERT INTO "public"."changelog" VALUES (3, 1, 1, 1, 3, '2024-01-17 14:29:29.409484');
INSERT INTO "public"."changelog" VALUES (4, 2, 1, 3, 1, '2024-01-17 14:31:24.765554');
INSERT INTO "public"."changelog" VALUES (5, 2, 2, 1, 4, '2024-01-17 14:33:21.07619');
INSERT INTO "public"."changelog" VALUES (6, 2, 2, 1, 5, '2024-01-17 14:34:36.669157');
INSERT INTO "public"."changelog" VALUES (7, 2, 2, 1, 6, '2024-01-17 14:35:22.022593');
INSERT INTO "public"."changelog" VALUES (8, 2, 2, 1, 7, '2024-01-17 14:36:15.318609');
INSERT INTO "public"."changelog" VALUES (9, 2, 2, 1, 8, '2024-01-17 14:37:56.269276');

-- ----------------------------
-- Table structure for event_types
-- ----------------------------
DROP TABLE IF EXISTS "public"."event_types";
CREATE TABLE "public"."event_types" (
  "eventTypeID" int4 NOT NULL DEFAULT nextval('"event_types_eventTypeID_seq"'::regclass),
  "name" varchar(30) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of event_types
-- ----------------------------
INSERT INTO "public"."event_types" VALUES (1, 'create');
INSERT INTO "public"."event_types" VALUES (2, 'update');
INSERT INTO "public"."event_types" VALUES (3, 'complete');
INSERT INTO "public"."event_types" VALUES (4, 'uncomplete');

-- ----------------------------
-- Table structure for group_members
-- ----------------------------
DROP TABLE IF EXISTS "public"."group_members";
CREATE TABLE "public"."group_members" (
  "memberID" int4 NOT NULL DEFAULT nextval('"group_members_groupID_seq"'::regclass),
  "groupID" int4 NOT NULL,
  "userID" int4 NOT NULL
)
;

-- ----------------------------
-- Records of group_members
-- ----------------------------
INSERT INTO "public"."group_members" VALUES (1, 1, 1);
INSERT INTO "public"."group_members" VALUES (2, 2, 1);
INSERT INTO "public"."group_members" VALUES (3, 1, 2);
INSERT INTO "public"."group_members" VALUES (4, 2, 2);

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS "public"."groups";
CREATE TABLE "public"."groups" (
  "groupID" int4 NOT NULL DEFAULT nextval('"groups_groupID_seq"'::regclass),
  "name" varchar(30) COLLATE "pg_catalog"."default" NOT NULL,
  "createdAt" timestamp(6) DEFAULT CURRENT_TIMESTAMP,
  "inviteCode" varchar(12) COLLATE "pg_catalog"."default" NOT NULL,
  "ownerMemberID" int4
)
;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO "public"."groups" VALUES (1, 'Roommates', '2024-01-17 14:23:21.706753', '9d2caa', 1);
INSERT INTO "public"."groups" VALUES (2, 'School project', '2024-01-17 14:23:37.295899', '417753', 2);

-- ----------------------------
-- Table structure for notes
-- ----------------------------
DROP TABLE IF EXISTS "public"."notes";
CREATE TABLE "public"."notes" (
  "noteID" int4 NOT NULL DEFAULT nextval('"notes_noteID_seq"'::regclass),
  "objectID" int4 NOT NULL,
  "title" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "content" varchar(255) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of notes
-- ----------------------------
INSERT INTO "public"."notes" VALUES (1, 3, 'Groccery list', 'Milk
Butter
Bread
Tomatoes');
INSERT INTO "public"."notes" VALUES (2, 7, 'Project outline', 'This project is about something, I don''t know it though. ');
INSERT INTO "public"."notes" VALUES (3, 8, 'Sources', 'Data was taken from these websties:

www.wikipedia.org
www.twitter.com

!Don''t forget to mention it in the presentation!');

-- ----------------------------
-- Table structure for objects
-- ----------------------------
DROP TABLE IF EXISTS "public"."objects";
CREATE TABLE "public"."objects" (
  "objectID" int4 NOT NULL DEFAULT nextval('"objects_objectID_seq"'::regclass),
  "groupID" int4 NOT NULL DEFAULT nextval('"objects_groupID_seq"'::regclass),
  "creator" int4 NOT NULL DEFAULT nextval('objects_creator_seq'::regclass),
  "createdAt" timestamp(6) DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of objects
-- ----------------------------
INSERT INTO "public"."objects" VALUES (1, 1, 1, '2024-01-17 14:28:38.3455');
INSERT INTO "public"."objects" VALUES (2, 1, 1, '2024-01-17 14:29:00.28063');
INSERT INTO "public"."objects" VALUES (3, 1, 1, '2024-01-17 14:29:29.371986');
INSERT INTO "public"."objects" VALUES (4, 2, 2, '2024-01-17 14:33:21.022153');
INSERT INTO "public"."objects" VALUES (5, 2, 2, '2024-01-17 14:34:36.634954');
INSERT INTO "public"."objects" VALUES (6, 2, 2, '2024-01-17 14:35:21.981836');
INSERT INTO "public"."objects" VALUES (7, 2, 2, '2024-01-17 14:36:15.19011');
INSERT INTO "public"."objects" VALUES (8, 2, 2, '2024-01-17 14:37:56.230277');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS "public"."sessions";
CREATE TABLE "public"."sessions" (
  "sessionID" varchar(32) COLLATE "pg_catalog"."default" NOT NULL,
  "userID" int4 NOT NULL DEFAULT nextval('"sessions_userID_seq"'::regclass),
  "validUntil" timestamp(6) NOT NULL
)
;

-- ----------------------------
-- Records of sessions
-- ----------------------------

-- ----------------------------
-- Table structure for tasks
-- ----------------------------
DROP TABLE IF EXISTS "public"."tasks";
CREATE TABLE "public"."tasks" (
  "taskID" int4 NOT NULL DEFAULT nextval('"tasks_taskID_seq"'::regclass),
  "objectID" int4 NOT NULL,
  "title" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "finishState" bit(1) NOT NULL,
  "assignedUser" int4,
  "dueDate" timestamp(6)
)
;

-- ----------------------------
-- Records of tasks
-- ----------------------------
INSERT INTO "public"."tasks" VALUES (2, 2, 'Take out garbage', '0', 1, NULL);
INSERT INTO "public"."tasks" VALUES (1, 1, 'Clean bathroom', '1', 2, NULL);
INSERT INTO "public"."tasks" VALUES (3, 4, 'Gather information', '0', 1, '2024-01-24 00:00:00');
INSERT INTO "public"."tasks" VALUES (4, 5, 'Make mockup', '0', NULL, '2024-01-30 00:00:00');
INSERT INTO "public"."tasks" VALUES (5, 6, 'Check with guidelines', '0', 2, '2024-01-25 00:00:00');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "public"."users";
CREATE TABLE "public"."users" (
  "userID" int4 NOT NULL DEFAULT nextval('"users_userID_seq"'::regclass),
  "email" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "password" varchar(138) COLLATE "pg_catalog"."default" NOT NULL,
  "name" varchar(30) COLLATE "pg_catalog"."default" NOT NULL,
  "createdAt" timestamp(6) DEFAULT CURRENT_TIMESTAMP,
  "activeGroupID" int4
)
;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO "public"."users" VALUES (1, 'andrew@gmail.com', 'a69708dee32a26bee8a0af1bd37ee7e42b390eaff7a91f6f5f38a7852599225518b552b3fd160419b5e32b789c87af013c7933541ba05987eff101009bcd4d3c373c5b24ca', 'Andrew', '2024-01-17 14:21:41.814322', 2);
INSERT INTO "public"."users" VALUES (3, 'dave@gmail.com', '68ff9a0dc8b4b5267e319803eb787428112d3c5281d07593c460773e4fc13a1459cabac72ad2350f65f1d969509b3d0057640f841c085fbba62ae3f13bb768b6a0d4ed4446', 'Dave', '2024-01-17 14:56:41.278438', NULL);
INSERT INTO "public"."users" VALUES (2, 'camila@gmail.com', '2e942e468257e6ca963d2fe63a52567944bfec0ce22f43a5ce2859d983e886da7e3bb74b860eca70d749116cdbd504451f4ea7c8e6c9ca3e6a091aacdab930226a4db0162e', 'Camila', '2024-01-17 14:26:04.82782', 2);

-- ----------------------------
-- Function structure for delete_expired_sessions
-- ----------------------------
DROP FUNCTION IF EXISTS "public"."delete_expired_sessions"();
CREATE OR REPLACE FUNCTION "public"."delete_expired_sessions"()
  RETURNS "pg_catalog"."void" AS $BODY$BEGIN
		DELETE FROM sessions WHERE CURRENT_DATE > "validUntil";

	RETURN;
END$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

-- ----------------------------
-- Function structure for ensure_capitalized_name
-- ----------------------------
DROP FUNCTION IF EXISTS "public"."ensure_capitalized_name"();
CREATE OR REPLACE FUNCTION "public"."ensure_capitalized_name"()
  RETURNS "pg_catalog"."trigger" AS $BODY$BEGIN
		NEW.name = INITCAP(NEW.name);
			
	RETURN NEW;
END$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

-- ----------------------------
-- View structure for object_types
-- ----------------------------
DROP VIEW IF EXISTS "public"."object_types";
CREATE VIEW "public"."object_types" AS  SELECT objects."objectID",
    tasks."taskID",
    NULL::integer AS "noteID"
   FROM objects
     LEFT JOIN tasks USING ("objectID")
  WHERE tasks."taskID" IS NOT NULL
UNION
 SELECT objects."objectID",
    NULL::integer AS "taskID",
    notes."noteID"
   FROM objects
     LEFT JOIN notes USING ("objectID")
  WHERE notes."noteID" IS NOT NULL;

-- ----------------------------
-- View structure for user_objects_per_group
-- ----------------------------
DROP VIEW IF EXISTS "public"."user_objects_per_group";
CREATE VIEW "public"."user_objects_per_group" AS  SELECT objects.creator AS "userID",
    objects."groupID",
    count(*) AS "objectCount"
   FROM objects
     JOIN users ON users."userID" = objects.creator
  GROUP BY objects.creator, objects."groupID";

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."changelog_eventID_seq"
OWNED BY "public"."changelog"."eventID";
SELECT setval('"public"."changelog_eventID_seq"', 9, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."changelog_eventTypeID_seq"
OWNED BY "public"."changelog"."eventTypeID";
SELECT setval('"public"."changelog_eventTypeID_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."changelog_groupID_seq"
OWNED BY "public"."changelog"."groupID";
SELECT setval('"public"."changelog_groupID_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."changelog_initiator_seq"
OWNED BY "public"."changelog"."initiator";
SELECT setval('"public"."changelog_initiator_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."changelog_targetObjectID_seq"
OWNED BY "public"."changelog"."targetObjectID";
SELECT setval('"public"."changelog_targetObjectID_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."event_types_eventTypeID_seq"
OWNED BY "public"."event_types"."eventTypeID";
SELECT setval('"public"."event_types_eventTypeID_seq"', 5, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."group_members_groupID_seq"
OWNED BY "public"."group_members"."memberID";
SELECT setval('"public"."group_members_groupID_seq"', 4, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."group_members_roleID_seq"
OWNED BY "public"."group_members"."userID";
SELECT setval('"public"."group_members_roleID_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."group_members_userID_seq"
OWNED BY "public"."group_members"."groupID";
SELECT setval('"public"."group_members_userID_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."groups_groupID_seq"
OWNED BY "public"."groups"."groupID";
SELECT setval('"public"."groups_groupID_seq"', 2, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."notes_noteID_seq"
OWNED BY "public"."notes"."noteID";
SELECT setval('"public"."notes_noteID_seq"', 3, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."objects_creator_seq"
OWNED BY "public"."objects"."creator";
SELECT setval('"public"."objects_creator_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."objects_groupID_seq"
OWNED BY "public"."objects"."groupID";
SELECT setval('"public"."objects_groupID_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."objects_objectID_seq"
OWNED BY "public"."objects"."objectID";
SELECT setval('"public"."objects_objectID_seq"', 8, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."sessions_userID_seq"
OWNED BY "public"."sessions"."userID";
SELECT setval('"public"."sessions_userID_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."tasks_taskID_seq"
OWNED BY "public"."tasks"."taskID";
SELECT setval('"public"."tasks_taskID_seq"', 5, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."users_userID_seq"
OWNED BY "public"."users"."userID";
SELECT setval('"public"."users_userID_seq"', 3, true);

-- ----------------------------
-- Primary Key structure for table changelog
-- ----------------------------
ALTER TABLE "public"."changelog" ADD CONSTRAINT "changelog_pkey" PRIMARY KEY ("eventID");

-- ----------------------------
-- Primary Key structure for table event_types
-- ----------------------------
ALTER TABLE "public"."event_types" ADD CONSTRAINT "event_types_pkey" PRIMARY KEY ("eventTypeID");

-- ----------------------------
-- Primary Key structure for table group_members
-- ----------------------------
ALTER TABLE "public"."group_members" ADD CONSTRAINT "group_members_pkey" PRIMARY KEY ("memberID");

-- ----------------------------
-- Primary Key structure for table groups
-- ----------------------------
ALTER TABLE "public"."groups" ADD CONSTRAINT "groups_pkey" PRIMARY KEY ("groupID");

-- ----------------------------
-- Uniques structure for table notes
-- ----------------------------
ALTER TABLE "public"."notes" ADD CONSTRAINT "notes_objectID_key" UNIQUE ("objectID");

-- ----------------------------
-- Primary Key structure for table notes
-- ----------------------------
ALTER TABLE "public"."notes" ADD CONSTRAINT "notes_pkey" PRIMARY KEY ("noteID");

-- ----------------------------
-- Primary Key structure for table objects
-- ----------------------------
ALTER TABLE "public"."objects" ADD CONSTRAINT "objects_pkey" PRIMARY KEY ("objectID");

-- ----------------------------
-- Primary Key structure for table sessions
-- ----------------------------
ALTER TABLE "public"."sessions" ADD CONSTRAINT "sessions_pkey" PRIMARY KEY ("sessionID");

-- ----------------------------
-- Uniques structure for table tasks
-- ----------------------------
ALTER TABLE "public"."tasks" ADD CONSTRAINT "tasks_objectID_key" UNIQUE ("objectID");

-- ----------------------------
-- Primary Key structure for table tasks
-- ----------------------------
ALTER TABLE "public"."tasks" ADD CONSTRAINT "tasks_pkey" PRIMARY KEY ("taskID");

-- ----------------------------
-- Triggers structure for table users
-- ----------------------------
CREATE TRIGGER "bef_ins|upd_name" BEFORE INSERT OR UPDATE ON "public"."users"
FOR EACH ROW
EXECUTE PROCEDURE "public"."ensure_capitalized_name"();

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("userID");

-- ----------------------------
-- Foreign Keys structure for table changelog
-- ----------------------------
ALTER TABLE "public"."changelog" ADD CONSTRAINT "changelog_eventTypeID_fkey" FOREIGN KEY ("eventTypeID") REFERENCES "public"."event_types" ("eventTypeID") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."changelog" ADD CONSTRAINT "changelog_groupID_fkey" FOREIGN KEY ("groupID") REFERENCES "public"."groups" ("groupID") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."changelog" ADD CONSTRAINT "changelog_initiator_fkey" FOREIGN KEY ("initiator") REFERENCES "public"."users" ("userID") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."changelog" ADD CONSTRAINT "changelog_targetObjectID_fkey" FOREIGN KEY ("targetObjectID") REFERENCES "public"."objects" ("objectID") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table group_members
-- ----------------------------
ALTER TABLE "public"."group_members" ADD CONSTRAINT "group_members_roleID_fkey" FOREIGN KEY ("userID") REFERENCES "public"."users" ("userID") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."group_members" ADD CONSTRAINT "group_members_userID_fkey" FOREIGN KEY ("groupID") REFERENCES "public"."groups" ("groupID") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table groups
-- ----------------------------
ALTER TABLE "public"."groups" ADD CONSTRAINT "groups_ownerMemberID_fkey" FOREIGN KEY ("ownerMemberID") REFERENCES "public"."group_members" ("memberID") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table notes
-- ----------------------------
ALTER TABLE "public"."notes" ADD CONSTRAINT "notes_objectID_fkey" FOREIGN KEY ("objectID") REFERENCES "public"."objects" ("objectID") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table objects
-- ----------------------------
ALTER TABLE "public"."objects" ADD CONSTRAINT "objects_creator_fkey" FOREIGN KEY ("creator") REFERENCES "public"."users" ("userID") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."objects" ADD CONSTRAINT "objects_groupID_fkey" FOREIGN KEY ("groupID") REFERENCES "public"."groups" ("groupID") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table sessions
-- ----------------------------
ALTER TABLE "public"."sessions" ADD CONSTRAINT "sessions_userID_fkey" FOREIGN KEY ("userID") REFERENCES "public"."users" ("userID") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table tasks
-- ----------------------------
ALTER TABLE "public"."tasks" ADD CONSTRAINT "tasks_assignedUser_fkey" FOREIGN KEY ("assignedUser") REFERENCES "public"."users" ("userID") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."tasks" ADD CONSTRAINT "tasks_objectID_fkey" FOREIGN KEY ("objectID") REFERENCES "public"."objects" ("objectID") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "users_activeGroupID_fkey" FOREIGN KEY ("activeGroupID") REFERENCES "public"."groups" ("groupID") ON DELETE SET NULL ON UPDATE CASCADE;
