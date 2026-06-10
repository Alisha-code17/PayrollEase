<?php
return [
    // Use multiple question variations for better matching
    //['question' => 'Explain this project.', 'answer' => 'This is a web application named "PayrollEase" it is created by three girls Alisha, Hifza and Bisma.'],
    ['question' => 'Explain this system.', 'answer' => '**PayrollEase** is a web-based payroll management system designed to do salary processing, attendance tracking, and employee management for organizations. The system provides a centralized dashboard where admins can manage various payroll components such as employee records, allowances, deductions, attendance, and salary generation.'],
    ['question' => 'Why the name PayrollEase?', 'answer' => 'The system is named PayrollEase because its primary goal is to simplify and streamline the payroll process through an intuitive and user-friendly web application. The name reflects the system’s focus on "ease"—offering a great UI (User Interface) and enhancing UX (User Experience) to ensure that both admins and employees can navigate and use the system effortlessly. By combining the words "Payroll" and "Ease," the name clearly communicates its purpose: to make payroll management easier, smoother, and more efficient for all users.'],
    ['question' => 'What are the different modules in this system?', 'answer' => 'The PayrollEase system is divided into several functional modules, each responsible for a specific part of payroll. The main modules include:
    Employee
    Department
    Attendance
    Leave
    Report
    Adjustments
    Payroll Generation '],
    ['question' => 'Explain each module.', 'answer' => '1. Employee Module
    Manages employee information such as personal details, job titles, and assigned departments.
    2. Department Module
    Handles the creation and management of different departments within the organization.
    3. Attendance Module
    Tracks daily or monthly attendance records of employees, including present and absent days.
    4. Leave Module
    Allows employees to apply for leave and enables admins to approve, reject, or manage leave requests.
    5. Adjustments Module
    Manages any additional allowances, bonuses, or deductions outside the standard salary structure.
    6. Report Module
    Provides payroll, attendance, and leave reports that can be filtered by employee, department, month, or year.
    7. Payroll Generation Module
    Calculates gross and net salaries based on attendance, allowances, deductions, and generates monthly payroll records.

    Each module contributes to the overall functionality of the system and is accessible through the dashboard menu, ensuring organized and efficient payroll processing.
    '],
    ['question' => 'Employee Module.', 'answer' => 'The Employee Module in the PayrollEase system is responsible for managing all employee-related information. It serves as the foundation for other modules such as attendance, payroll, and leave management.
    It contains submenus that are:
    **1. Add Employee**
    This section allows the admin to register a new employee by entering essential details such as:
    Full Name, Email, Contact Number, Department, Designation, Basic Salary, Joining Date, etc.
    **2. Manage Employee**
    This section enables the admin to view, update, or delete existing employee records. Admins can:
    Edit employee details, Search and filter employees, Assign or change department/designation, etc.'],
    
    ['question' => 'Department Module.', 'answer' => 'The Department Module in the PayrollEase system is designed to manage the organizational structure by handling departments and job designations. It plays a key role in categorizing employees and aligning them with their respective roles.
    It contains submenus that are:
    **1. Manage Department**
    This section allows the admin to create, update, or delete departments within the organization (e.g., HR, IT, Finance). Each department acts as a grouping for employees performing similar functions.
    **2. Manage Designation**
    This section is used to define various job titles or positions (e.g., Manager, Developer, Accountant). Designations are linked to departments and are used when adding or editing employee records.
    '],
    ['question' => 'Attendance Module.', 'answer' => 'The Attendance Module in the PayrollEase system is responsible for recording and managing employee attendance. It ensures that attendance data is accurately captured and used in payroll calculations.
    The submenu is:
    **Employee Attendance**
    In this section, the admin marks the monthly attendance of each employee for the previous month. The form typically includes:
    Selection of the employee
    Number of Present Days
    Number of Absent Days
    Overtime Hours
    The corresponding month and year
    '],
    ['question' => 'Leave Module.', 'answer' => 'The Leave Module in the PayrollEase system is designed to manage employee leave requests and their approval statuses. It helps track leaves efficiently and ensures that leave records are considered during payroll processing.
    Its submenu is:
    **Status Leave**
    In this section, the admin can view the status of leave requests submitted by employees. The status can be:
    Pending (awaiting approval)
    Approved
    Rejected'],
    ['question' => 'Report Module.', 'answer' => 'The Report Module in the PayrollEase system provides detailed insights and summaries related to employees, attendance, and leave. It helps the admin monitor workforce data, evaluate performance, and support payroll decisions through organized reports and Export as PDF and print feature.
    It contains submenus that are:
    **1. Employee Report**
    Displays a list of all employees along with their basic information such as department, designation, contact details, and joining date. It serves as a quick reference for HR-related data.
    **2. Attendance Report**
    Shows employee attendance records for selected months. Admins can filter reports by month and year to view present/absent days per employee. This is useful for payroll verification and performance tracking.
    **3. Leave Report**
    Provides a summary of all leave applications, including leave dates, reasons, types, and approval statuses. This helps in reviewing leave patterns and ensuring fair leave management.
    '],
    ['question' => 'Adjustments Module.', 'answer' => 'The Adjustments Module in the PayrollEase system manages the financial adjustments that directly impact an employee’s salary, specifically allowances and deductions. This module ensures accurate and flexible payroll calculations based on individual employee cases.
    It contains submenus that are:
    **1. Allowances**
    Used to assign additional earnings to employees, such as transport allowance, housing allowance, or other bonuses. Admins can specify the amount and type of allowance for each employee.
    **2. Allowances List**
    Displays a list of all existing allowances added to the system.
    **3. Deductions**
    Allows the admin to apply specific deductions to employee salaries, such as tax, penalties, or loan repayments.
    **4. Deductions List**
    Displays a list of all existing deductions added to the system.
    '],
    ['question' => 'Payroll Generation Module.', 'answer' => 'The Payroll Generation Module in the PayrollEase system is responsible for calculating and generating employee salaries based on various factors, including attendance, allowances, deductions, and bonuses. This module automates the process of salary computation, ensuring accuracy and efficiency.
    It contains submenus that are:
    **1. Payroll Profile**
    This section allows admins to define and manage payroll profiles for each employee. It includes setting up salary structures, allowances, deductions, and bonus categories. Payroll profiles serve as templates for generating accurate payroll records.
    **2. Payroll Computation**
    Here, the admin can calculate employee salaries for a specific month. The system automatically computes:
    Gross Salary = Basic Salary + Allowances + Bonuses
    Net Salary = Gross Salary - Deductions
    This computation is based on data from the Attendance, Allowances, and Deductions modules. The payroll computation ensures accurate salary generation and is ready for review before finalization.
    **3. Payslip Log**
    In this section, the payslips for all employees are generated and stored. It maintains a detailed record of all the payslips issued, including the breakdown of salaries, allowances, deductions, and the final net pay.
    '],
    // Add more variations as needed
    //['question' => 'Explain this system.', 'answer' => '**PayrollEase** is a web-based payro'],
];

