const responses = {
    learner: [
        {
            question: "What is the deadline for assignments?",
            answer: "The deadline for assignments is every Friday at 5 PM.",
            keywords: ["deadline", "assignments", "due date"]
        },
        {
            question: "How do I access my grades?",
            answer: "You can access your grades through the student portal under the 'Grades' section.",
            keywords: ["access", "grades", "check grades"]
        },
        {
            question: "What should I do if I am struggling with a subject?",
            answer: "It's best to reach out to your teacher for additional help or consider joining a study group.",
            keywords: ["struggling", "help", "subject"]
        },
        {
            question: "Are there any upcoming events?",
            answer: "Yes, we have a school fair next month. Check the announcements for details.",
            keywords: ["upcoming", "events", "school fair"]
        },
        {
            question: "How can I join a club?",
            answer: "You can join a club by signing up during the club fair or contacting the club leader.",
            keywords: ["join", "club", "membership"]
        },
        {
            question: "Where can I find study resources?",
            answer: "Study resources can be found on the library website and the student portal.",
            keywords: ["study resources", "find", "library"]
        },
        {
            question: "What are the school's policies on absences?",
            answer: "You can find the school's policies on absences in the student handbook.",
            keywords: ["policies", "absences", "attendance"]
        },
        {
            question: "How do I contact my teacher?",
            answer: "You can contact your teacher via email or through the messaging system on the student portal.",
            keywords: ["contact", "teacher", "email"]
        },
        {
            question: "What is the format for submitting assignments?",
            answer: "Assignments should be submitted electronically through the student portal.",
            keywords: ["submit", "assignments", "format"]
        },
        {
            question: "Can I retake an exam?",
            answer: "Retaking an exam is subject to teacher approval. Please discuss it with your teacher.",
            keywords: ["retake", "exam", "teacher approval"]
        }
    ],
    admin: [
        {
            question: "How can I reset a student's password?",
            answer: "You can reset a student's password by going to the user management section.",
            keywords: ["reset", "password", "student"]
        },
        {
            question: "How do I access the school reports?",
            answer: "Access the reports through the admin dashboard under the 'Reports' tab.",
            keywords: ["access", "school reports", "admin dashboard"]
        },
        {
            question: "What is the process for hiring new staff?",
            answer: "The hiring process involves posting the job, reviewing applications, and conducting interviews.",
            keywords: ["hiring", "staff", "process"]
        },
        {
            question: "How can I manage school events?",
            answer: "You can manage school events through the events management system.",
            keywords: ["manage", "school events", "events management"]
        },
        {
            question: "Where can I find budget reports?",
            answer: "Budget reports are located in the financial management section.",
            keywords: ["budget reports", "financial management"]
        },
        {
            question: "How do I update student records?",
            answer: "You can update student records in the student information system.",
            keywords: ["update", "student records", "information system"]
        },
        {
            question: "What is the procedure for handling complaints?",
            answer: "Complaints should be documented and addressed according to school policy.",
            keywords: ["complaints", "procedure", "policy"]
        },
        {
            question: "How can I communicate with teachers?",
            answer: "You can communicate with teachers through the admin messaging system.",
            keywords: ["communicate", "teachers", "messaging system"]
        },
        {
            question: "What are the responsibilities of the admin team?",
            answer: "The admin team is responsible for managing school operations, staff, and student affairs.",
            keywords: ["responsibilities", "admin team", "operations"]
        },
        {
            question: "How do I handle emergency procedures?",
            answer: "Emergency procedures can be found in the school safety manual.",
            keywords: ["emergency", "procedures", "safety manual"]
        }
    ],
    teacher: [
        {
            question: "How can I submit grades?",
            answer: "You can submit grades through the grading system on the teacher's portal.",
            keywords: ["submit", "grades", "grading system"]
        },
        {
            question: "What is the policy on late assignments?",
            answer: "Late assignments may incur penalties, as stated in the course syllabus.",
            keywords: ["policy", "late assignments", "penalties"]
        },
        {
            question: "How do I communicate with parents?",
            answer: "You can communicate with parents through the parent-teacher messaging system.",
            keywords: ["communicate", "parents", "messaging system"]
        },
        {
            question: "What resources are available for lesson planning?",
            answer: "Resources for lesson planning can be found in the teacher resource section of the portal.",
            keywords: ["resources", "lesson planning", "teacher portal"]
        },
        {
            question: "How can I manage classroom behavior?",
            answer: "Classroom behavior can be managed by setting clear expectations and consequences.",
            keywords: ["manage", "classroom behavior", "expectations"]
        },
        {
            question: "What is the process for requesting supplies?",
            answer: "To request supplies, fill out the supply request form available in the admin section.",
            keywords: ["request", "supplies", "form"]
        },
        {
            question: "How do I access student progress reports?",
            answer: "Student progress reports can be accessed through the grading system.",
            keywords: ["access", "student progress reports", "grading system"]
        },
        {
            question: "What is the best way to handle a conflict with a student?",
            answer: "Handle conflicts with students by addressing issues directly and calmly.",
            keywords: ["handle", "conflict", "student"]
        },
        {
            question: "How can I provide feedback on assignments?",
            answer: "Feedback on assignments can be provided through the grading comments section.",
            keywords: ["provide", "feedback", "assignments"]
        },
        {
            question: "What professional development opportunities are available?",
            answer: "Professional development opportunities are listed in the staff development.",
            keywords: ["professional development", "opportunities", "staff development"]
        }
    ],
    parent: [
        {
            question: "How can I check my child's grades?",
            answer: "You can check your child's grades through the parent portal using your login credentials.",
            keywords: ["check", "child's grades", "parent portal"]
        },
        {
            question: "What should I do if my child is struggling?",
            answer: "If your child is struggling, please contact their teacher to discuss possible support options.",
            keywords: ["struggling", "child", "support options"]
        },
        {
            question: "How do I get involved in school events?",
            answer: "You can get involved in school events by volunteering or attending meetings organized by the school.",
            keywords: ["involved", "school events", "volunteering"]
        },
        {
            question: "What is the school's policy on attendance?",
            answer: "The school's attendance policy can be found in the parent handbook available on the school website.",
            keywords: ["policy", "attendance", "parent handbook"]
        },
        {
            question: "How can I communicate with my child's teacher?",
            answer: "You can communicate with your child's teacher via email or through the parent-teacher communication platform.",
            keywords: ["communicate", "teacher", "email"]
        },
        {
            question: "What are the upcoming parent-teacher conferences?",
            answer: "Upcoming parent-teacher conferences are scheduled for next month. Please check the school calendar for details.",
            keywords: ["upcoming", "conferences", "parent-teacher"]
        },
        {
            question: "How do I access school resources for parents?",
            answer: "School resources for parents can be accessed through the parent portal on the school website.",
            keywords: ["access", "resources", "parents"]
        },
        {
            question: "What support services are available for my child?",
            answer: "Support services for students, including counseling and tutoring, are available and can be requested through the school.",
            keywords: ["support services", "available", "child"]
        },
        {
            question: "How can I stay informed about school updates?",
            answer: "You can stay informed about school updates by subscribing to the school newsletter and checking the website regularly.",
            keywords: ["stay informed", "school updates", "newsletter"]
        },
        {
            question: "What should I do if I have concerns about my child's safety?",
            answer: "If you have concerns about your child's safety, please report them to the school administration immediately.",
            keywords: ["concerns", "child's safety", "report"]
        }
    ]
};


