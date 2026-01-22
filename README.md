🍽️ Restaurant Ordering System (PHP & MySQL)

This project is an application of a web-based restaurant ordering system.

It´s meant to digitize the process of taking orders in a restaurant, allowing waiters to create, manage, and print orders and invoices using tablets or smartphones.

🎯 Project Goals

Digital creation of restaurant orders
Assigning orders to restaurant tables
Managing orders (view, edit, delete)
Displaying and printing invoices

🛠️ Technology Stack

PHP (procedural, PDO)
MySQL (InnoDB, foreign keys)
HTML5 / CSS3
Bootstrap 5
JavaScript (for printing via browser)

🔄 Application Workflow / Functions

The waiter opens the app and selects "Karte" to open the menu
The system loads all tables and menu items
The waiter selects a table enters quantities next to desired menu items
Clicking "Create Order" submits the form
Backend logic creates one order (based on multiple order-positions)
Orders window shows all orders, displays table number, timestamp, and total amount
Allows editing, deleting, or viewing the invoice
Aetting quantity to 0 removes an item from the order
Invoices from each order can be viewed
They show invoice items, quantities, prices and total
A print-optimized invoice can be generated (window.print())

🔐 Security

PDO with prepared statements for user input
No direct SQL with user-provided values
Transactions ensure data consistency
Server-side validation for table selection and empty orders
