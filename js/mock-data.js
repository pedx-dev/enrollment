
class EnrollmentDatabase {
    constructor() {
        this.initializeData();
    }

    initializeData() {
        if (!localStorage.getItem('enrollment_db')) {
            const mockData = {
                students: [
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
                            address: "123 Main St, Quezon City, Metro Manila",
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
                        status: "active",
                        documents: [],
                        createdAt: new Date('2024-06-15').getTime()
                    },
                    {
                        id: "26-00002",
                        fullName: {
                            first: "Juan",
                            middle: "Miguel",
                            last: "Cruz"
                        },
                        personalInfo: {
                            dob: "2003-08-22",
                            pob: "Cebu",
                            sex: "Male",
                            status: "Single",
                            nationality: "Filipino",
                            religion: "Roman Catholic"
                        },
                        contact: {
                            address: "456 Oak Ave, Cebu City, Cebu",
                            phone: "+63-9175552345",
                            email: "juan.cruz@email.com"
                        },
                        guardian: {
                            name: "Rosa Cruz",
                            phone: "+63-9175556789",
                            relationship: "Mother"
                        },
                        academic: {
                            course: "Bachelor of Science in Information Technology",
                            year: "Second Year",
                            section: "B",
                            enrollmentDate: "2023-06-20"
                        },
                        status: "active",
                        documents: [],
                        createdAt: new Date('2023-06-20').getTime()
                    },
                    {
                        id: "26-00003",
                        fullName: {
                            first: "Angela",
                            middle: "Rose",
                            last: "Reyes"
                        },
                        personalInfo: {
                            dob: "2004-02-10",
                            pob: "Davao",
                            sex: "Female",
                            status: "Single",
                            nationality: "Filipino",
                            religion: "Evangelical"
                        },
                        contact: {
                            address: "789 Pine Rd, Davao City, Davao",
                            phone: "+63-9175553456",
                            email: "angela.reyes@email.com"
                        },
                        guardian: {
                            name: "Fernando Reyes",
                            phone: "+63-9175557890",
                            relationship: "Father"
                        },
                        academic: {
                            course: "Bachelor of Arts in Business Administration",
                            year: "First Year",
                            section: "A",
                            enrollmentDate: "2024-06-18"
                        },
                        status: "active",
                        documents: [],
                        createdAt: new Date('2024-06-18').getTime()
                    }
                ],
                teachers: [
                    {
                        id: "T001",
                        name: "Prof. John Smith",
                        email: "john.smith@college.edu",
                        department: "Computer Science",
                        specialization: "Data Science",
                        phone: "+63-2-8123-4567",
                        courses: ["CS101", "CS201"]
                    },
                    {
                        id: "T002",
                        name: "Dr. Maria Garcia",
                        email: "maria.garcia@college.edu",
                        department: "Business Administration",
                        specialization: "Finance",
                        phone: "+63-2-8123-4568",
                        courses: ["BA101", "BA202"]
                    },
                    {
                        id: "T003",
                        name: "Prof. Michael Johnson",
                        email: "michael.johnson@college.edu",
                        department: "Computer Science",
                        specialization: "Web Development",
                        phone: "+63-2-8123-4569",
                        courses: ["CS102", "CS203"]
                    }
                ],
                courses: [
                    {
                        id: "CS101",
                        code: "CS101",
                        name: "Introduction to Programming",
                        department: "Computer Science",
                        credits: 3,
                        description: "Fundamentals of programming using Python",
                        semester: "First Semester",
                        instructor: "Prof. John Smith"
                    },
                    {
                        id: "CS103",
                        code: "CS103",
                        name: "Discrete Mathematics",
                        department: "Computer Science",
                        credits: 3,
                        description: "Logic, sets, functions, and combinatorics",
                        semester: "First Semester",
                        instructor: "Prof. John Smith"
                    },
                    {
                        id: "CS105",
                        code: "CS105",
                        name: "Computer Organization",
                        department: "Computer Science",
                        credits: 3,
                        description: "CPU, memory, and low-level data representation",
                        semester: "First Semester",
                        instructor: "Prof. Michael Johnson"
                    },
                    {
                        id: "CS102",
                        code: "CS102",
                        name: "Web Development",
                        department: "Computer Science",
                        credits: 3,
                        description: "HTML, CSS, and JavaScript fundamentals",
                        semester: "Second Semester",
                        instructor: "Prof. Michael Johnson"
                    },
                    {
                        id: "CS201",
                        code: "CS201",
                        name: "Data Structures",
                        department: "Computer Science",
                        credits: 3,
                        description: "Arrays, lists, stacks, queues, trees, graphs",
                        semester: "Second Semester",
                        instructor: "Prof. John Smith"
                    },
                    {
                        id: "CS203",
                        code: "CS203",
                        name: "Database Systems",
                        department: "Computer Science",
                        credits: 3,
                        description: "Relational databases and SQL",
                        semester: "Second Semester",
                        instructor: "Prof. Michael Johnson"
                    },
                    {
                        id: "BA101",
                        code: "BA101",
                        name: "Business Fundamentals",
                        department: "Business Administration",
                        credits: 3,
                        description: "Introduction to business concepts",
                        semester: "First Semester",
                        instructor: "Dr. Maria Garcia"
                    },
                    {
                        id: "BA102",
                        code: "BA102",
                        name: "Principles of Management",
                        department: "Business Administration",
                        credits: 3,
                        description: "Planning, organizing, leading, and controlling",
                        semester: "First Semester",
                        instructor: "Dr. Maria Garcia"
                    },
                    {
                        id: "BA202",
                        code: "BA202",
                        name: "Financial Management",
                        department: "Business Administration",
                        credits: 3,
                        description: "Budgeting, forecasting, and financial analysis",
                        semester: "Second Semester",
                        instructor: "Dr. Maria Garcia"
                    },
                    {
                        id: "IT101",
                        code: "IT101",
                        name: "Information Technology Essentials",
                        department: "Information Technology",
                        credits: 3,
                        description: "Basics of IT systems",
                        semester: "First Semester",
                        instructor: "Prof. John Smith"
                    },
                    {
                        id: "IT102",
                        code: "IT102",
                        name: "Networking Fundamentals",
                        department: "Information Technology",
                        credits: 3,
                        description: "Network models, protocols, and routing",
                        semester: "Second Semester",
                        instructor: "Prof. John Smith"
                    }
                ],
                enrollments: [
                    {
                        id: "E001",
                        studentId: "26-00001",
                        courseId: "CS101",
                        enrollmentDate: "2024-06-15",
                        status: "active"
                    },
                    {
                        id: "E002",
                        studentId: "26-00001",
                        courseId: "CS102",
                        enrollmentDate: "2024-06-15",
                        status: "active"
                    },
                    {
                        id: "E003",
                        studentId: "26-00002",
                        courseId: "CS101",
                        enrollmentDate: "2023-06-20",
                        status: "completed"
                    }
                ],
                settings: {
                    collegeName: "HOLYCROSSCORE",
                    collegeCode: "26",
                    nextStudentNumber: 4,
                    academicYear: "2024-2025",
                    semester: "First Semester"
                }
            };
            localStorage.setItem('enrollment_db', JSON.stringify(mockData));
        }
    }

    getData() {
        const data = localStorage.getItem('enrollment_db');
        return data ? JSON.parse(data) : null;
    }

    saveData(data) {
        localStorage.setItem('enrollment_db', JSON.stringify(data));
    }

    // Student Operations
    getAllStudents() {
        const db = this.getData();
        return db.students;
    }

    getStudentById(id) {
        const db = this.getData();
        return db.students.find(s => s.id === id);
    }

    addStudent(studentData) {
        const db = this.getData();
        const newStudent = {
            id: this.generateNextStudentId(),
            ...studentData,
            status: 'active',
            documents: [],
            createdAt: new Date().getTime()
        };
        db.students.push(newStudent);
        this.saveData(db);
        return newStudent;
    }

    updateStudent(id, updates) {
        const db = this.getData();
        const studentIndex = db.students.findIndex(s => s.id === id);
        if (studentIndex !== -1) {
            db.students[studentIndex] = {
                ...db.students[studentIndex],
                ...updates
            };
            this.saveData(db);
            return db.students[studentIndex];
        }
        return null;
    }

    deleteStudent(id) {
        const db = this.getData();
        db.students = db.students.filter(s => s.id !== id);
        this.saveData(db);
        return true;
    }

    searchStudents(query) {
        const db = this.getData();
        const lowerQuery = query.toLowerCase();
        return db.students.filter(s => 
            s.fullName.first.toLowerCase().includes(lowerQuery) ||
            s.fullName.last.toLowerCase().includes(lowerQuery) ||
            s.id.includes(lowerQuery) ||
            s.contact.email.toLowerCase().includes(lowerQuery)
        );
    }

    generateNextStudentId() {
        const db = this.getData();
        const collegeCode = db.settings.collegeCode;
        const nextNumber = String(db.settings.nextStudentNumber).padStart(5, '0');
        const newId = `${collegeCode}-${nextNumber}`;
        db.settings.nextStudentNumber++;
        this.saveData(db);
        return newId;
    }

    // Course Operations
    getAllCourses() {
        const db = this.getData();
        return db.courses;
    }

    getCourseById(id) {
        const db = this.getData();
        return db.courses.find(c => c.id === id);
    }

    // Teacher Operations
    getAllTeachers() {
        const db = this.getData();
        return db.teachers;
    }

    getTeacherById(id) {
        const db = this.getData();
        return db.teachers.find(t => t.id === id);
    }

    getTeacherByName(name) {
        const db = this.getData();
        const normalized = (name || '').trim().toLowerCase();
        if (!normalized) return null;
        return db.teachers.find(t => (t.name || '').trim().toLowerCase() === normalized) || null;
    }

    getTeacherCourses(teacherName) {
        const db = this.getData();
        const normalized = (teacherName || '').trim().toLowerCase();
        if (!normalized) return [];
        return db.courses.filter(c => (c.instructor || '').trim().toLowerCase() === normalized);
    }

    getStudentCountForCourse(courseId) {
        const db = this.getData();
        if (!courseId) return 0;
        const studentIds = new Set(db.enrollments
            .filter(e => e.courseId === courseId)
            .map(e => e.studentId));
        return studentIds.size;
    }

    getStudentsForTeacher(teacherName, courseId = '') {
        const db = this.getData();
        const courses = this.getTeacherCourses(teacherName);
        const courseIds = courseId
            ? courses.filter(c => c.id === courseId || c.code === courseId).map(c => c.id)
            : courses.map(c => c.id);

        if (courseIds.length === 0) return [];

        const studentIds = new Set(
            db.enrollments
                .filter(e => courseIds.includes(e.courseId))
                .map(e => e.studentId)
        );

        return db.students.filter(s => studentIds.has(s.id));
    }

    // Enrollment Operations
    getAllEnrollments() {
        const db = this.getData();
        return db.enrollments;
    }

    getEnrollmentsByStudent(studentId) {
        const db = this.getData();
        return db.enrollments.filter(e => e.studentId === studentId);
    }

    addEnrollment(enrollmentData) {
        const db = this.getData();
        const newEnrollment = {
            id: 'E' + (db.enrollments.length + 1).toString().padStart(3, '0'),
            ...enrollmentData,
            enrollmentDate: new Date().toISOString().split('T')[0],
            status: 'active'
        };
        db.enrollments.push(newEnrollment);
        this.saveData(db);
        return newEnrollment;
    }

    // Settings Operations
    getSettings() {
        const db = this.getData();
        return db.settings;
    }

    updateSettings(updates) {
        const db = this.getData();
        db.settings = { ...db.settings, ...updates };
        this.saveData(db);
        return db.settings;
    }

    // Statistics
    getStudentStats() {
        const db = this.getData();
        const total = db.students.length;
        const active = db.students.filter(s => s.status === 'active').length;
        const newThisMonth = db.students.filter(s => {
            const createdDate = new Date(s.createdAt);
            const now = new Date();
            return createdDate.getMonth() === now.getMonth() && 
                   createdDate.getFullYear() === now.getFullYear();
        }).length;

        return {
            total,
            active,
            newThisMonth
        };
    }

    getTotalEnrollments() {
        const db = this.getData();
        return db.enrollments.length;
    }
}

// Initialize database
const enrollmentDB = new EnrollmentDatabase();
