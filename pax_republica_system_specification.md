# Pax Republica 2.0 - System Specification Document

## Table of Contents
1. [Introduction](#introduction)
2. [System Overview](#system-overview)
3. [Database Schema](#database-schema)
4. [User Management System](#user-management-system)
5. [Character System](#character-system)
6. [Scene Management System](#scene-management-system)
7. [Job System](#job-system)
8. [Faction System](#faction-system)
9. [Dice Rolling System](#dice-rolling-system)
10. [Admin Tools](#admin-tools)
11. [Implementation Plan](#implementation-plan)

## Introduction

This document outlines the comprehensive system specification for Pax Republica 2.0, a web-based roleplaying platform designed to replace the existing PennMUSH system. The new system aims to provide all the functionality of the current system with improved user experience, modern web interface, and enhanced features.

Pax Republica is a Star Wars roleplaying game set in the Old Republic era. The system needs to support character creation and management, scene creation and participation, dice rolling for skill checks, faction management, and administrative tools.

## System Overview

### Architecture

The system will be built using Laravel framework with the following components:

-   **Frontend**: Blade templates with modern CSS and JavaScript
-   **Backend**: PHP/Laravel
-   **Database**: MySQL
-   **Authentication**: Laravel's built-in authentication system

### Key Features

1.  **User Account Management**
    *   Registration with email verification
    *   Profile management
    *   Character management
    *   Messaging system

2.  **Character System**
    *   Character creation with attributes, skills, and traits
    *   Character advancement using XP
    *   Character approval workflow
    *   Vehicle and NPC management

3.  **Scene Management**
    *   Scene creation and participation
    *   Posting system
    *   Dice rolling integration
    *   Plot tracking

4.  **Job System**
    *   Ticket-based support system
    *   Multiple categories for different request types
    *   Admin assignment and resolution workflow

5.  **Faction System**
    *   Faction membership and ranks
    *   Faction management tools

6.  **Admin Tools**
    *   User management
    *   Content management
    *   System configuration

## Database Schema

### Users Table
```
users
- id (primary key)
- account_name (string, max 8 chars, alphanumeric)
- email (string, unique)
- password (string, hashed)
- timezone (string)
- real_name (string, nullable)
- age (integer, nullable)
- sex (enum: 'M', 'F', null)
- profile (text, nullable)
- is_admin (boolean, default false)
- is_active (boolean, default true)
- created_at (timestamp)
- updated_at (timestamp)
```

### Characters Table
```
characters
- id (primary key)
- user_id (foreign key to users)
- name (string)
- status (enum: 'ACTIVE', 'INACTIVE', 'PENDING', 'INPROGRESS')
- species_id (foreign key to species)
- faction_id (foreign key to factions, nullable)
- rank_id (foreign key to faction_ranks, nullable)
- age (integer)
- occupation (string)
- hair (string, nullable)
- eyes (string, nullable)
- height (string, nullable)
- profile (text)
- background (text)
- picture_url (string, nullable)
- tier (integer)
- is_force_user (boolean, default false)
- banked_xp (integer, default 0)
- total_xp (integer)
- last_active_at (timestamp, nullable)
- approved_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Character Attributes Table
```
character_attributes
- id (primary key)
- character_id (foreign key to characters)
- attribute_id (foreign key to attributes)
- value (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### Character Skills Table
```
character_skills
- id (primary key)
- character_id (foreign key to characters)
- skill_id (foreign key to skills)
- value (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### Character Specializations Table
```
character_specializations
- id (primary key)
- character_id (foreign key to characters)
- specialization_id (foreign key to specializations)
- value (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### Character Traits Table
```
character_traits
- id (primary key)
- character_id (foreign key to characters)
- trait_id (foreign key to traits)
- created_at (timestamp)
- updated_at (timestamp)
```

### Attributes Table (System Defined)
```
attributes
- id (primary key)
- name (string)
- description (text)
- created_at (timestamp)
- updated_at (timestamp)
```

### Skills Table (System Defined)
```
skills
- id (primary key)
- name (string)
- description (text)
- attribute_id (foreign key to attributes)
- created_at (timestamp)
- updated_at (timestamp)
```

### Specializations Table (System Defined)
```
specializations
- id (primary key)
- name (string)
- description (text)
- skill_id (foreign key to skills)
- created_at (timestamp)
- updated_at (timestamp)
```

### Traits Table (System Defined)
```
traits
- id (primary key)
- name (string)
- description (text)
- modifiers (json)
- created_at (timestamp)
- updated_at (timestamp)
```

### Species Table (System Defined)
```
species
- id (primary key)
- name (string)
- description (text)
- modifiers (json)
- wiki_url (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Vehicles Table
```
vehicles
- id (primary key)
- character_id (foreign key to characters)
- template_id (foreign key to vehicle_templates)
- name (string)
- description (text, nullable)
- picture_url (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Vehicle Templates Table (System Defined)
```
vehicle_templates
- id (primary key)
- name (string)
- description (text)
- stats (json)
- created_at (timestamp)
- updated_at (timestamp)
```

### Vehicle Mods Table
```
vehicle_mods
- id (primary key)
- vehicle_id (foreign key to vehicles)
- mod_template_id (foreign key to mod_templates)
- created_at (timestamp)
- updated_at (timestamp)
```

### Mod Templates Table (System Defined)
```
mod_templates
- id (primary key)
- name (string)
- description (text)
- stats_modifier (json)
- created_at (timestamp)
- updated_at (timestamp)
```

### NPCs Table
```
npcs
- id (primary key)
- character_id (foreign key to characters)
- name (string)
- species_id (foreign key to species)
- description (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### NPC Attributes Table
```
npc_attributes
- id (primary key)
- npc_id (foreign key to npcs)
- attribute_id (foreign key to attributes)
- value (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### NPC Skills Table
```
npc_skills
- id (primary key)
- npc_id (foreign key to npcs)
- skill_id (foreign key to skills)
- value (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### Factions Table (System Defined)
```
factions
- id (primary key)
- name (string)
- description (text)
- color (string)
- picture_url (string, nullable)
- wiki_url (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Faction Ranks Table (System Defined)
```
faction_ranks
- id (primary key)
- faction_id (foreign key to factions)
- name (string)
- level (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### Archetypes Table (System Defined)
```
archetypes
- id (primary key)
- name (string)
- description (text)
- build_data (json)
- created_at (timestamp)
- updated_at (timestamp)
```

### Scenes Table
```
scenes
- id (primary key)
- title (string)
- location_id (foreign key to locations)
- synopsis (text, nullable)
- plot_id (foreign key to plots, nullable)
- creator_id (foreign key to users)
- creator_character_id (foreign key to characters)
- status (enum: 'ACTIVE', 'FINISHED')
- last_activity_at (timestamp)
- updated_at (timestamp)
```

### Scene Participants Table
```
scene_participants
- id (primary key)
- scene_id (foreign key to scenes)
- character_id (foreign key to characters)
- joined_at (timestamp)
- updated_at (timestamp)
```

### Posts Table
```
posts
- id (primary key)
- scene_id (foreign key to scenes)
- character_id (foreign key to characters)
- content (text)
- created_at (timestamp)
- updated_at (timestamp)
```

### Post Edits Table
```
post_edits
- id (primary key)
- post_id (foreign key to posts)
- previous_content (text)
- edited_at (timestamp)
- created_at (timestamp)
- updated_at (timestamp)
```

### Locations Table (System Defined)
```
locations
- id (primary key)
- name (string)
- description (text, nullable)
- picture_url (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Plots Table
```
plots
- id (primary key)
- title (string)
- description (text, nullable)
- creator_id (foreign key to users)
- created_at (timestamp)
- updated_at (timestamp)
```

### Jobs Table
```
jobs
- id (primary key)
- category (enum: 'ADVANCEMENT', 'APPLICATIONS', 'BUG_REPORTS', 'FEEDBACK', 'PITCH', 'REWORK', 'TP')
- title (string)
- creator_id (foreign key to users)
- handler_id (foreign key to users, nullable)
- status (enum: 'OPEN', 'CLOSED', 'APPROVED', 'REJECTED', 'CANCELED')
- character_id (foreign key to characters, nullable)
- last_activity_at (timestamp)
- created_at (timestamp)
- updated_at (timestamp)
```

### Job Comments Table
```
job_comments
- id (primary key)
- job_id (foreign key to jobs)
- user_id (foreign key to users)
- content (text)
- created_at (timestamp)
- updated_at (timestamp)
```

### Job Comment Edits Table
```
job_comment_edits
- id (primary key)
- job_comment_id (foreign key to job_comments)
- previous_content (text)
- edited_at (timestamp)
- updated_at (timestamp)
```

### Messages Table
```
messages
- id (primary key)
- sender_id (foreign key to users)
- recipient_id (foreign key to users)
- subject (string)
- content (text)
- is_read (boolean, default false)
- created_at (timestamp)
- updated_at (timestamp)
```

### Votes Table
```
votes
- id (primary key)
- voter_character_id (foreign key to characters)
- voted_character_id (foreign key to characters)
- scene_id (foreign key to scenes)
- created_at (timestamp)
- updated_at (timestamp)
```

### Character Change Logs Table
```
character_change_logs
- id (primary key)
- character_id (foreign key to characters)
- changes (json)
- xp_cost (integer)
- status (enum: 'PENDING', 'APPROVED', 'REJECTED')
- job_id (foreign key to jobs, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

## Database Schema

## User Management System

### Registration Process
1.  User fills out registration form with email, password, account name (OOC handle), and timezone. Account name must be no more than 8 alphanumeric characters.
2.  System sends verification email with anti-spam measures.
3.  User verifies email and completes registration.
4.  User can optionally fill out profile fields such as real name, age, sex (M/F), and a little blurb about themselves as manual paragraph text entry. All the above information will be viewable by all members of the site except email.

### User Types
1.  **Player**
    *   Read and send inbox messages.
    *   Created characters are attached to the account.
    *   Create/edit and submit attached characters for approval.
    *   Create scenes and manage scenes they created (see Scenes & Locations).
    *   Jobs:
        *   Create
        *   Edit those that belong to them
        *   Edit comments that belong to them

2.  **Admin** - All the permissions players have, in addition to:
    *   Reviewing and approving/unapproving characters and character upgrades or changes
    *   Jobs (see Jobsys):
        *   Cancel any job
        *   Assign/unassign handler
    *   Moderation powers such as deactivate/reactivate/delete player accounts and removing characters from player accounts. Deactivated accounts cannot be accessed by the player.
    *   Can also issue sitebans on IPs which will block it from registration entirely
    *   Notifications include any character status changes made by players
    *   Create locations (see Scenes & Locations)
    *   Create/edit/manage/delete any scenes regardless of ownership or scene status
    *   View/edit all accounts and associated/characters vehicles
        *   Account name - email - date created on each line, click to view their own account page
        *   add/remove/edit/reassign characters/vehicles on accounts
        *   Delete, deactivate or reactivate accounts
        *   Add/remove banked XP from characters
        *   Change status of any character between any of the statuses (ACTIVE, INACTIVE, INPROGRESS, PENDING)
        *   Adding, deleting or changing items in the system
            *   Skills, Specializations, Attributes
            *   Traits
            *   Characters
            *   Vehicles

### User Interface

#### Toolbar
*   Always visible regardless of which page is open
*   Buttons:
    *   If not logged in:
        *   login/register button which will open a drop-down with a login or register option
    *   If logged in as a player:
        *   Account page
        *   Create new scene which will open scene creation dialog (See Scenes & Locations)
        *   Scenes which will show a list of currently active scenes (SceneID#, Title, character participant’s name) with the ability to click on any of them to jump there. If none, will show a message that says “No active scenes”
    *   If user has character(s) in a scene or scenes,
        *   Notifications to show if there are new:
            *   Inbox messages
            *   Job activity
            *   Scene activity if user has a character in a scene(s)
        *   Ability to click the notification icon and see a list of notification items that can be clicked to go to the source of the notification.
    *   Regardless of login status:
        *   Discord permalink <https://discord.gg/mSkfFkpngY> (also shows if logged in)
        *   Scene list - Takes the user to a page with a list of all scenes ordered first by recent activity and second by creation date. (See Scenes & Locations)

#### Account page:

*   inbox where they can send and receive private messages to other accounts
*   View/edit profile fields mentioned in Account creation
*   list of created characters with the following columns (See Characters & Vehicles for more info):
    *   Name
    *   Major Faction
    *   Status (ACTIVE, INACTIVE, PENDING, INPROGRESS) - Users can change status of characters between ACTIVE and INACTIVE with the following restrictions (per Characters & Alternates policy):
        *   If a character has been INACTIVE for more than six months, the user cannot change it to ACTIVE without admin approval and they will need to submit a job
        *   Players may not have more than a total of 6 ACTIVE characters on their account at once, and no more than 1 of them may be a Force user. Note that they may have more than 1 Force user but only 1 of them may be ACTIVE at a time
        *   If the account already has at least 1 character in the ACTIVE status, only 1 character may be reactivated from INACTIVE status once every 14 days. If there are no characters with the ACTIVE status, 2 characters may be activated within the 14 day window. This also applies to newly created characters moving from INPROGRESS to ACTIVE after admin approval.
        *   If another character was reactivated less than 14 days ago, warn the player if they try to deactivate a character, informing them that they will not be able to reactivate it again until the 14 days since the other character’s reactivation has passed, unless the account fits the above criteria
    *   Date of first approval if applicable, if not then N/A
    *   Date of last post to a SceneID.
    *   Current banked XP (See Character Advancement)
    *   Force user (Y/N) - this can be an icon that is either grey for N and or some other bolder color for Y. A Force user is defined as any character that has Force skills on its sheet.
    *   Users can click each character to visit that character’s page (see Characters & Vehicles)
*   Jobs table (see Jobsys).

## Character System

### Character Creation
1.  Users will be able to create a new character from their Account page where the character list is or from the Home page (see Home).
2.  The character creation screen will show a blank form structured after the character sheets where users will be able to assign values to each field according to how many points they are allotted during character generation, and respecting limits according to the Race they selected. This will be based on the character’s Tier.
    *   Users will set Profile (a brief character overview), select a Major faction if desired, and select a Rank within that faction.
    *   Characters are divided into tiers based on how many XP their character is worth:
        *   Tier 1 - 700 and below -> receive 10 skill points to spend in Character generation (CG)
        *   Tier 2 - 750 -> receive 20 skill points to spend in CG
        *   Tier 3 - 800 -> 30 skill points
        *   Tier 4 - 850+ -> 40 skill points
    *   They will also be able to optionally select an Archetype which will auto-assign a build previously created. However they can also make changes to the sheet themselves after selecting the Archetype as well.
3.  Once a user is finished creating their character, they can click a “Submit” button which will automatically send a Job to admin in the Applications category (see Jobsys). When a character is first created, its status is INPROGRESS by default. Submitting will move the stats from INPROGRESS to PENDING. The job will have a link to the pending character’s sheet for admin to review. Once an admin approves the job, the character will move from PENDING to ACTIVE. Only characters in the ACTIVE state may be selected when registering to a sceneID.

### Character States
*   **INPROGRESS**: Initial state during creation
*   **PENDING**: Submitted for approval
*   **ACTIVE**: Approved and available for scenes
*   **INACTIVE**: Temporarily disabled

### Character Advancement & Reworks
*   Advancement
    *   Players will be able to upgrade their characters and vehicles using XP earned through voting (see Scenes & Locations)
    *   Players may view their character sheets by clicking on their character from the list on the Account page (See Registration & Accounts). “Edit” button allows the user to make changes to their character’s sheet, spending XP earned by that character for any changes that increase the character’s overall XP value. If any changes have been made, the user has the option to “discard” changes or “submit” them for approval. Submitting will create a Job in the Applications (see Jobsys) for admin’s attention containing a link to the character sheet that is pending update. Admin will be able to see a changelog for the proposed updates in a different color or something easy to see.
*   Rework
    *   A rework is any change to a character’s sheet that does not increase its overall XP value. Downgrades or changes that decrease the character’s overall XP value are not permitted. Reworks will be processed similarly to upgrades, only it does not require XP to be spent.

### Vehicles, Mods, & NPCs
*   Vehicles may be added to as part of the Resource bonus and are attached to that character’s sheet.
*   NPCs may be added in a similar fashion and are attached to that character’s sheet. Only the owning player and admin may view a character’s sheet and its associated vehicle/NPC sheets.

## Scene Management System

### Scene Creation
1.  User creates scene from toolbar and will open a dialogue with Title, Location, Synopsis, PlotID, scene creator’s selected character and Participants list.
2.  If no title is specified at the time of creation, a placeholder with the scene number will be used instead (i.e. “Scene <ID#>”). When a user creates a scene, the scene page will automatically open. Show title, ID#, Location, expandable description at the top of the scene page.. A faded version of the Location image if available shows in the background of the page.
3.  Scenes have the following requirements:
    *   Must be created by a registered user and that user must select a character in the Active status
    *   Creator must select at least 1 other eligible character besides their own to add to the Participants list. All characters registered to a Scene must be in the Active status. Users may add active characters that also are attached to the same account as long as at least 1 active character associated with a different account is also registered.
4.  Upon creation, Scenes are in the Active state.

### Scene Tools
*   Special toolbar that only appears when a user visits a Scene.
    *   If the user is the scene owner/creator, they will be able to access scene management tools which will allow them to set/change the following properties:
        *   Title
        *   Location
        *   PlotID
        *   Synopsis
        *   Scene tools will allow the scene owner to end the scene or close it at which point it will move from the Active state to the Finished state.
    *   If the user is not the owner/creator, they will be able to access the following functions:
        *   Join - Option to select a character belonging to the user that is the active status and register it to the Scene.
        *   Once joined, the following functions become available (also available to scene owner/creator):
            *   Vote - Vote for any other character(s) or all character(s) registered to the scene that does not belong to the user (see Character Advancement).
            *   Post - Send a pose to the scene. This should be a free text paragraph entry, preferably with support for rich text formatting and ability to expand the text box for longer poses.
            *   Edit an existing pose belonging to the user. If a pose is edited, edit history may be viewed by all users. Only poses in a scene marked Active may be edited by regular users. Admin can edit anytime.
            *   Dice rolls - Opens a dialog giving the user the option to roll any skill on the sheet belonging to the character registered to the scene, with all automatic modifiers automatically applied. Also gives the user the option to manually add any situational modifiers, and posts the results of the roll to the scene log.

### Scene list
*   The table columns will be as follows: SceneID# - Title - Location - Status (Active or Finished) - Date/Time stamp of most recent activity. Default sort is by most recent activity
*   Clicking on the scenes in the list will take the user to that Scene page

### Plots
*   PlotID numbers may be assigned to sceneIDs, allowing users to view scenes by PlotID#. Clicking on the plotID# in the list of properties at the top of the scene page shows the user a list of scenes with that plotID assigned.

## Factions
*   Each faction has a description and ranks.
*   The faction will show in a character’s Profile as selected during character creation and the name will link to a faction page with description, optional picture, list of members and their rank, and link to wiki pages as above. Factions also have a color which will be applied to the names of every member wherever they appear.
*   Highest ranking faction members may add, remove, or change rank of member characters with lower rank. Admin can do this for any members, as well as edit description, picture, wiki link, faction name, rank names/numbers, and color.

## Dice Rolling System

*   There are 2 basic types of rolls:
    *   Standard Roll - This rolls the selected character/NPC/vehicle’s Skill, Specialization, Attribute, or Vehicle stat taking into account any relevant automatic modifiers as dictated by the character’s Traits and Race if applicable. Vehicles have different stats to roll but do not have any Skills or Specializations (see Characters & Vehicles). NPCs are the same as player characters but do not have any Traits. Users may also add their own manually entered situational modifiers. It should report the result of base roll and then any modifiers applied, including any manually added situational modifiers. Players should be able to turn off the verbose rolls if they want however, and have it only report the results to them.
    *   Challenge Roll - This rolls the selected character, NPC, or Vehicle against another character or another character’s NPC or vehicle, taking into account any automatic modifiers from Race or Traits. This will make 2 rolls, one for each selected character/NPC/vehicle and Skill/Attribute/Specialization/Vehicle stat and compare them, then report the higher roll and the amount by which it was higher. This roll should also report base rolls plus all modifiers including manually added situational modifiers by default. As for standard rolls, a user may turn off the verbose roll and have it only report the results.
*   Rolling tools should be included in the tools available on active Scene pages (see Scenes & Locations).

## Admin Tools

*   Infrastructure for admin to add/remove/edit the following items in the system:
    *   Skills - Add new, edit description, edit name, edit base Attribute, remove
    *   Attributes - Add new, edit description, edit name, edit modifiers
    *   Archetypes - Add new, edit, remove
    *   Races - Add new, edit, remove
    *   Factions - Add new, remove, edit name, edit rank names/numbers, add/remove ranks, add/remove members, change member rank, add/edit picture, change faction color
    *   Specializations -Add new, edit description, edit name, edit base parent Skill, remove
    *   Traits - Add new, edit description, edit name, edit modifiers
    *   Vehicle templates - Add new, remove, edit name, edit description, edit stats, add/edit mods
    *   Vehicle mods - Add new, remove, edit name, edit description, edit stats
    *   Locations/Places - Add new, remove, edit name, edit description, edit image

## Job System

### Concept:
*   Jobsys is essentially a ticketing system that players can use to get admin support for various purposes. Players can create a job in the following categories/buckets:
    *   Advancement - existing character upgrades (see Character Advancement)
    *   Applications - new character applications (See Characters & Vehicles)
    *   Bug Reports - self-explanatory. If there is an issue somewhere, players can report it in this category
    *   Feedback - suggestions for improvement
    *   Pitch - Character concept pitch, where players can give a brief description of the concept they have in mind to make sure it is workable before putting effort into a sheet.
    *   Rework - existing character modifications that are not upgrades
    *   TP - This is where players can submit requests for plots they want to run themselves or want admin to run.
*   Jobs can be opened from a user’s account page (see Registration & Accounts). Users can see a table listing jobs they have created with the following columns:
    *   ID number
    *   Bucket
    *   Owner
    *   Title
    *   Most recent activity date
    *   Staff handler
    *   Status (open or closed)
*   Players will only see jobs they created, Admin are able to see all jobs. The table may be filtered and sorted by any field, shows only open jobs by default.
*   Clicking on a job in the table opens the job and shows all comments. If a comment has been edited, users are able to view the edit history. It would be nice if Application, Rework, and Advancement jobs also automatically contained links to the character sheet being submitted for approval.

### The following roles will have the associated functions/permissions:
*   User:
    *   Add comments
    *   Edit comments they created
    *   Cancel jobs they created
    *   Re-open jobs they canceled
*   Admin
    *   All user rights
    *   Approve, reject, or cancel any job
    *   Re-open any previously closed job (whether approved, rejected, or canceled)

</file_content>

IMPORTANT: For any future changes to this file, use the final_file_content shown above as your reference. This content reflects the current state of the file, including any auto-formatting (e.g., if you used single quotes but the formatter converted them to double quotes). Always base your SEARCH/REPLACE operations on this final version to ensure accuracy.

<environment_details>
# VSCode Visible Files
pax_republica_system_specification.md

# Current Time
4/6/2025, 7:13:37 AM (Asia/Karachi, UTC+5:00)

# Current Mode
PLAN MODE
In this mode you should focus on information gathering, asking questions, and architecting a solution. Once you have a plan, use the plan_mode_respond tool to engage in a conversational back and forth with the user. Do not use the plan_mode_respond tool until you've gathered all the information you need e.g. with read_file or ask_followup_question.
(Remember: If it seems the user wants you to use tools only available in Act Mode, you should ask the user to "toggle to Act mode" (use those words) - they will have to manually do this themselves with the Plan/Act toggle button below. You do not have the ability to switch to Act Mode yourself, and must wait for the user to do it themselves once they are satisfied with the plan. You also cannot present an option to toggle to Act mode, as this will be something you need to direct the user to do manually themselves.)
</environment_details>
