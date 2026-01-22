🍽️ Restaurant Ordering System (PHP & MySQL)

This project is an application of a web-based restaurant ordering system.

It´s meant to digitize the process of taking orders in a restaurant, allowing waiters to create, manage, and print orders and invoices using tablets or smartphones.

🎯 Project Goals

Digital creation of restaurant orders<br>
Assigning orders to restaurant tables<br>
Managing orders (view, edit, delete)<br>
Displaying and printing invoices

🛠️ Technology Stack

PHP (procedural, PDO)<br>
MySQL (InnoDB, foreign keys)<br>
HTML5 / CSS3<br>
Bootstrap 5<br>
JavaScript (for printing via browser)

🔄 Application Workflow / Functions

The waiter opens the app and selects "Karte" to open the menu<br>
The system loads all tables and menu items<br>
The waiter selects a table enters quantities next to desired menu items<br>
Clicking "Create Order" submits the form<br>
Backend logic creates one order (based on multiple order-positions)<br>
Orders window shows all orders, displays table number, timestamp, and total amount<br>
Allows editing, deleting, or viewing the invoice<br>
Aetting quantity to 0 removes an item from the order<br>
Invoices from each order can be viewed<br>
They show invoice items, quantities, prices and total<br>
A print-optimized invoice can be generated (window.print())

🔐 Security

PDO with prepared statements for user input<br>
No direct SQL with user-provided values<br>
Transactions ensure data consistency<br>
Server-side validation for table selection and empty orders
