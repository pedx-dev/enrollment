# College Enrollment System

A complete college enrollment management system built with PHP, MySQL, Tailwind CSS, DaisyUI, and Heroicons. Features admin and teacher dashboards with full database integration.

## Features

### Admin Dashboard
- **Student Enrollment**: Multi-step form for enrolling new students with auto-generated IDs
- **Student Management**: View, edit, search, and manage student records
- **Course Management**: Manage available courses and programs
- **Teacher Management**: Directory of faculty members and their courses
- **Dashboard Overview**: Statistics and quick actions
- **Responsive Design**: Mobile-friendly interface with collapsible sidebar

### Teacher Dashboard
- **My Classes**: View assigned courses and sections
- **Student Roster**: Complete list of enrolled students
- **Attendance Tracking**: Mark and record attendance
- **Dashboard Overview**: Quick stats and recent activities

### Key Features
- **MySQL Database**: All data stored in MySQL database with PDO
- **User Authentication**: Role-based login system (Admin/Teacher) with password hashing
- **Teacher Registration**: Teachers can register and await admin approval
- **Approval Workflow**: Admin approves/rejects teacher registrations
- **Responsive UI**: Works on desktop, tablet, and mobile devices
- **Modern Design**: Built with Tailwind CSS and DaisyUI components
- **Icons**: Heroicons and Font Awesome for visual enhancement
- **Search & Filter**: Advanced filtering and search capabilities
- **Modal Dialogs**: Interactive forms and confirmations using SweetAlert2
- **AJAX Operations**: Smooth CRUD operations without page reloads

## Project Structure

```
college-enrollment-system/
├── index.php                 # Login page
├── register.php              # Teacher registration page
├── database/
│   └── enrollment_db.sql    # MySQL database schema
├── css/                      # Custom styles folder
├── includes/
│   ├── config.php           # Configuration and session management
│   └── database.php         # Database helper functions (CRUD)
├── admin/
│   ├── dashboard.php        # Main admin dashboard
│   ├── overview.php         # Dashboard overview/statistics
│   ├── enrollment.php       # Student enrollment form
│   ├── students.php         # Student management
│   ├── courses.php          # Course management
│   ├── teachers.php         # Teacher directory
│   └── approvals.php        # Teacher registration approvals
├── teacher/
│   ├── dashboard.php        # Main teacher dashboard
│   ├── overview.php         # Dashboard overview
│   ├── classes.php          # My classes view
│   ├── roster.php           # Student roster
│   └── attendance.php       # Attendance tracking
└── README.md
```

## Installation & Setup

### Prerequisites
- XAMPP (PHP 7.0+ with Apache and MySQL)
- Modern web browser (Chrome, Firefox, Safari, Edge)

### Installation Steps

1. **Clone or Download**
   - Extract the project to: `C:\xampp\htdocs\enroll\`

2. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache and MySQL

3. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin/`
   - Create a new database named `enrollment_db`
   - Import the SQL file: `database/enrollment_db.sql`
   - Or run the SQL directly from the file

4. **Access the Application**
   - Open browser and go to: `http://localhost/enroll/`

5. **Login Credentials**

   **Admin Account:**
   - Email: `admin@college.edu`
   - Password: `password`

   **Teacher Account:**
   - Email: `teacher@college.edu`
   - Password: `password`

## Usage Guide

### For Administrators

1. **Login** with admin credentials
2. **Enroll New Students**
   - Click "New Enrollment" in sidebar
   - Fill in multi-step form (Personal, Contact, Guardian, Academic)
   - Auto-generated Student ID (e.g., HCC-00001)
   - Review and submit
3. **Manage Students**
   - Search by name, ID, or email
   - Filter by course or status
   - View detailed student information
   - Edit student details
   - Delete records
   - Export student list as CSV
4. **View Courses** - Browse all available courses
5. **Manage Teachers** - View faculty directory and assignments
6. **Dashboard** - View statistics and recent activities

### For Teachers

1. **Login** with teacher credentials
2. **View Classes** - See assigned courses and student counts
3. **Student Roster** - View all students in your classes
4. **Mark Attendance** - Record daily attendance
5. **Enter Grades** - Input student grades for assessments
6. **View Schedule** - Check your teaching schedule
7. **Dashboard** - See today's classes and quick stats

## Technology Stack

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **DaisyUI** - Tailwind CSS components library
- **Heroicons** - Beautiful SVG icons
- **JavaScript** - Client-side scripting

### Backend
- **PHP 7.0+** - Server-side logic
- **Session Management** - User authentication
- **LocalStorage** - Client-side data persistence

### Features Not Requiring Database
- User authentication (hardcoded demo credentials)
- Student data management
- Course information
- Teacher directory
- All data stored in browser localStorage

## Color Palette

- **Primary**: #FFFFFF (White)
- **Secondary**: #222431 (Dark Blue-Gray)
- **Accent**: #30425A (Medium Blue)
- **Complementary**: Blue, Green, Orange, Purple gradients

## Data Structure

### Student Object
```javascript
{
    id: "26-00001",
    fullName: {
        first: "Maria",
        middle: "Grace",
        last: "Santos"
    },
    personalInfo: {
        dob: "2003-05-15",
        pob: "Manila",
        sex: "Female",
        status: "Single",
        nationality: "Filipino",
        religion: "Roman Catholic"
    },
    contact: {
        address: "123 Main St, Quezon City",
        phone: "+63-9175551234",
        email: "maria.santos@email.com"
    },
    guardian: {
        name: "Juan Santos",
        phone: "+63-9175555678",
        relationship: "Father"
    },
    academic: {
        course: "Bachelor of Science in Computer Science",
        year: "First Year",
        section: "A",
        enrollmentDate: "2024-06-15"
    },
    status: "Enrolled",
    createdAt: timestamp
}
```

## Browser Compatibility

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

## Database

### Tables
- **settings** - System configuration (college code, semester, etc.)
- **users** - Admin and teacher accounts with hashed passwords
- **students** - Student records with personal and academic info
- **courses** - Available courses/programs
- **enrollments** - Student-course enrollments
- **attendance** - Attendance tracking records

### Database Functions (includes/database.php)

```php
// Student Operations
getAllStudents($filters = [])
getStudentById($id)
addStudent($data)
updateStudent($id, $data)
deleteStudent($id)

// Course Operations
getAllCourses()
getCourseById($id)

// Teacher Operations
getAllTeachers()
getTeacherById($id)

// Enrollment Operations
getAllEnrollments()

// Statistics
getStudentStats()
getTotalEnrollments()
getRecentStudents($limit = 5)

// Settings
getSettings()
```

## Customization

### Adding New Courses
1. Use phpMyAdmin or run SQL INSERT to add courses to `courses` table
2. The dropdowns will automatically populate from the database

### Changing Color Scheme
1. Edit CSS color variables in dashboard.php files:
   ```css
   :root {
       --color-primary: #FFFFFF;
       --color-secondary: #222431;
       --color-accent: #30425A;
   }
   ```

### Adding New Users
1. Insert new user into `users` table via phpMyAdmin
2. Use PHP's `password_hash('password', PASSWORD_DEFAULT)` to generate password hash
3. Set role to 'admin' or 'teacher'

## Features Implemented

- ✅ MySQL database integration
- ✅ User authentication with password hashing
- ✅ Student CRUD operations
- ✅ Course management
- ✅ Teacher directory
- ✅ Multi-step enrollment form
- ✅ Search and filtering
- ✅ AJAX operations
- ✅ Responsive design

## Future Enhancements

- [ ] Email notifications
- [ ] Document upload/storage
- [ ] Advanced reporting
- [ ] Parent portal
- [ ] Mobile app
- [ ] Real-time notifications
- [ ] SMS integration
- [ ] Grade management
- [ ] Schedule management

## Support

For issues or questions, please check:
1. Browser console for JavaScript errors
2. PHP error logs for backend issues
3. MySQL connection settings in `includes/config.php`

## License

This project is for educational purposes.

## Version

**Version**: 2.0.0  
**Last Updated**: February 2, 2026

---

**Developed with Tailwind CSS, DaisyUI, and Heroicons**
