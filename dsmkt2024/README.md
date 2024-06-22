# DS-MKT

## Public Access Before Login
The following views are available before user authentication:
- Login View
- Registration View
- Password Reminder View

### Features to be Enhanced
- Addition of a footer with:
    1. Social media links
    2. User manual and help desk contact email
    3. Copyright notice

- The site needs to be optimized for responsiveness and mobile device handling.
- Easy logo replacement and redirection of unauthorized users to a general page.

## User/Administrator Panel
Access to the user panel is available to all logged-in users. Access to the administrator panel is exclusively for users with administrator privileges (`user_group_id=1`) or caretakers assigned in the menu (`menu_owners` table).

### Top Menu
- **My Account**
    - Email notifications about changes (each user can set notifications for changes in file content within specific categories).
- **Change Password**
- **User Panel/Administrator Panel** (visible only to administrators and caretakers)
- **Logout**

### Search
Search functionality considers user permissions and searches within the database of files.

### Banner
Displays a random image from the gallery or one assigned to a specific section as defined by the administrator in the panel.

## User Panel
- Users see a menu structure consistent with their assigned permissions and can browse and download files within available tabs.

## Administrator Panel
### Concessions - Dealership Salons
- List
- Add New Concession
    - Name*
    - Address*
    - Postal Code*
    - City*
    - Phone*
    - Email instead of FAX (* required)
- Edit Concession

### Users
- List, edit, add, and manage user permissions
    - Groups (ability to edit group permissions)
    - Requests (accept user registrations)

### Menu Structure
- Add new tab
    - Define tab type (main, subordinate)
    - Tab name
    - Assign caretakers/administrators (optional)
    - Tab visibility date range (optional)
    - Assign banner (random or dedicated)
    - Option to delete
        - Confirmation prompt
        - Alert if the section contains subtabs or files
- Edit existing tabs
- Change order - list/tree view
- Toggle parameter (active/inactive)

### Cars (Managing Car Models)
### Files (File Management)
- Add file to a tab
    - File name
    - Option to upload file
    - File visibility date range (optional)
    - Keywords - tags (space-separated) (optional)
    - Assign to a car model (optional)
- Update file
    - Option to upload a new file (old file is removed from the server)
- Toggle parameter (active/inactive)

### Statistics
- Downloads
    - Today
    - Yesterday
    - Last 7 days
    - Last 30 days
    - Advanced - user-defined date range
    - Generate data from the selected range to xls
- Logins
- Page Visits

### Automated Reports (sent on the 1st of each month to):
- Administrators
    - List of users not logged in for the last 30, 90, 180 days
    - List of files not downloaded in the last 30, 90, 180 days
    - Introduce exceptions for specific users or files
- Caretakers
    - List of files not downloaded in the last 30, 90, 180 days
    - Introduce exceptions for specific files

## Development Plan
- Base site structure with basic authentication
- Code “Menu Structure” and import data from the old service
- Code “Concessions” and import data from the old service
- Code “Users”
    - Manage access via group and individual user
    - Handle user requests
    - Import data from the old service
- Code “Files” functionality and import data from the old service (including files)
- Code “Statistics” functionality

### Pre-Development Information Needed
- How you want to handle (what plugins, libraries, methodology):
    1. Menu structure (e.g., laravel-tree or something else like https://www.jstree.com/ for the frontend)
    2. User access
    3. File uploads (e.g., plupload, dropzone, or another)
