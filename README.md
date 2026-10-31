# Finler AI - Personal Finance Coach with a Living Money Model

Finler AI is an advanced, AI-powered personal finance coach built for the Agentic AI Hackathon. Unlike traditional budgeting apps that only categorize expenses, Finler AI builds a living financial model connecting daily transactions with long-term goals.

## Core Features

- AI Statement Ingestion: Automatically parses PDF and CSV bank statements using Google Gemini API.
- The Correction Loop: Allows users to correct miscategorized items; the system learns rules for future transactions.
- Smart Subscriptions: Manages recurring bills, utility expenses, and recurring payment tracking.
- Persistent Financial Profile: Tracks income, fixed costs, emergency funds, and target-date goals.
- What-If Goal Simulator: Interactive scenario planner to see how spending cuts accelerate goal completion.
- Spending Drift Nudges: Proactive warnings when discretionary spending goes off plan.
- Monthly Audit Reports: Certified print-ready and PDF review reports of inflows and outflows.

## Tech Stack

- Backend: PHP 8.2, MySQL (PDO)
- Frontend: Tailwind CSS, JavaScript, Chart.js
- AI Engine: Google Gemini 3.6 Flash API
- Voice Workflow: Wispr Flow

## Directory Structure

finler/
├── api/                   # JSON API endpoints
├── assets/                # Logos and icons
├── database/              # SQL database export (finler.sql)
├── includes/              # Database connection and templates
├── users/                 # User views and sidebars
├── .env.example           # Environment template
├── auth.php               # Authentication handler
├── dashboard.php          # Main dashboard view
├── export_pdf.php         # PDF financial statement exporter
├── index.php              # Landing page
├── login.php              # Login view
├── logout.php             # Logout handler
├── monthly_report.php     # Cashflow audit report
├── save_goal.php          # Financial goals handler
├── settings.php           # User settings view
├── signup.php             # Registration handler
├── sync_gmail.php         # Gmail statement sync handler
├── transactions.php       # Transactions management view
├── update_category.php    # Correction loop engine
└── upload_statement.php   # PDF/CSV statement uploader

## Installation and Setup

1. Clone the repository:
   git clone https://github.com/irrathodofficial/finler.git

2. Configure Database:
   - Open your MySQL / phpMyAdmin server.
   - Create a new database named `finler`.
   - Import the `database/finler.sql` file provided in this repository to automatically set up all required tables (`users`, `transactions`, `goals`, `subscriptions`, `financial_profiles`).

3. Set up Environment Variables:
   Copy `.env.example` to `.env` in your root directory and update your database and Gemini API credentials:
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=finler
   GEMINI_API_KEY=your_gemini_api_key_here

4. Run on Local Server:
   Place the project folder inside your local server directory (e.g., wamp/www/finler) and open http://localhost/finler.

## Security & Compliance
- Strict financial guardrails avoiding direct security trading advice.

Built with by Ishwar Rathod for the Product Space Agentic AI Hackathon using Wispr Flow.