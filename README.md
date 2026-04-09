# JobLeads 🚀

A full-stack Laravel job platform connecting job seekers with companies, featuring AI-powered CV review, multi-role authentication, and rich company dashboards.

---

## Project Structure

JobLeads is split into two Laravel applications sharing a single database:

| App | Description |
|-----|-------------|
| `job-Backoffice` | Admin & company owner dashboard |
| `job-app` | Public-facing job seeker application |

---

## Features

### job-Backoffice
- Multi-role authentication (Admin / Company Owner) via Laravel Breeze
- Role-based middleware protecting all routes
- **Admin panel:** full CRUD for Users, Companies, Job Categories, and Job Vacancies
- **Company Owner panel:** manage own company profile, view vacancies and applications
- Soft deletes with archive/restore/force-delete flows for all resources
- Company analytics dashboard (views, applications, conversion rates)
- AI-powered CV review system via OpenRouter (`stepfun/step-3.5-flash:free`)

### job-app
- Job seeker registration and login
- Browse and view job vacancies
- Apply to vacancies with resume upload
- AI-generated resume score and feedback on application
- Personal dashboard showing application history and status

---

## Roles

| Role | Access |
|------|--------|
| `admin` | Full access to all resources |
| `company_owner` | Manage own company, vacancies, and applications |
| `job_seeker` | Browse jobs and submit applications |

---

## Tech Stack

- **Framework:** Laravel 12
- **Auth:** Laravel Breeze
- **AI:** OpenRouter API via `openai-php/laravel`
- **File Storage:** Cloud storage for resume uploads
- **PDF Parsing:** `spatie/pdf-to-text`
- **Database:** MySQL (shared between both apps)

---

## Getting Started

### Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

### Installation

```bash
# Clone the repo
git clone https://github.com/marwan2096/job-leads.git
cd job-leads

# Install dependencies
composer install
npm install && npm run build

# Environment setup
cp .env.example .env
php artisan key:generate
```

Configure your `.env`:

```env
DB_DATABASE=job_leads
DB_USERNAME=root
DB_PASSWORD=

OPENROUTER_API_KEY=your_key_here
```

### Database Setup

```bash
php artisan migrate
php artisan db:seed
```

The seeder will create:
- **Admin account:** `admin@example.com` / `123456789`
- Sample companies with owner accounts
- Job categories and vacancies
- Sample job seekers with applications, resumes, and AI-generated feedback

---

## Routes Overview

### Backoffice (Admin + Company Owner)
| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Dashboard |
| GET/POST | `/vacancy` | List / Create vacancies |
| GET/PUT/DELETE | `/vacancy/{id}` | Show / Update / Delete vacancy |
| GET | `/vacancy/archive` | Archived vacancies |
| PUT | `/vacancy/{id}/restore` | Restore vacancy |
| DELETE | `/vacancy/{id}/force-delete` | Permanently delete |
| GET/POST | `/application` | List / Create applications |
| PUT | `/application/{id}/restore` | Restore application |

### Backoffice (Admin only)
| Method | URI | Description |
|--------|-----|-------------|
| Resource | `/user` | User management |
| Resource | `/company` | Company management |
| Resource | `/category` | Job category management |

### Backoffice (Company Owner only)
| Method | URI | Description |
|--------|-----|-------------|
| GET | `/my-company` | View own company |
| GET/PUT | `/my-company/edit` | Edit company profile |

### Job App (Authenticated job seekers)
| Method | URI | Description |
|--------|-----|-------------|
| GET | `/dashboard` | Job seeker dashboard |
| GET | `/job-applications` | My applications |
| GET | `/vacancy/{id}` | View vacancy details |
| GET/POST | `/vacancy/{id}/apply` | Apply to vacancy |

---

## License

MIT
