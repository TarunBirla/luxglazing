# LUX GLAZING UK
## Strategic Growth Proposal: AI Lead Generation, Enterprise CRM & UK Market Domination
**Project:** AI-Powered Digital Marketing, B2B Prospecting & Custom CRM Automation  
**Target Market:** United Kingdom (High-End Residential & Commercial Trade)  
**Client Website:** https://luxglazing.nexteck.uk/  
**Prepared By:** Digital Transformation, AI Automation & Growth Architecture Practice  
**Date:** November 2026 | Confidential Executive Proposal  

---

### Executive Overview
This document contains the complete business growth, AI automation, digital marketing, and technical architecture proposal designed specifically for **Lux Glazing UK**.

A full, beautifully-formatted executive version of this strategy proposal is also available in the conversation artifact:
`LUX_GLAZING_AI_GROWTH_STRATEGY_PROPOSAL.md`

---

## 1. One-Click Database Setup Route (Instructions for Live Server)

When uploading this Laravel application to your live server (cPanel, Shared Hosting, VPS, or Cloud Server):

1. **Upload files** to your server public directory or web root.
2. In your `.env` file on the server, configure your database details:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1 (or localhost)
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```
3. **Hit the URL in your browser:**
   ```
   https://yourdomain.com/setup-database
   ```
   *(Alternative aliases also supported: `/init-database` or `/db-migrate` or `/api/setup-database`)*

### What happens automatically when you hit that URL?
- Checks connection and creates the MySQL database if it does not exist.
- Runs all 5 database migrations (`--force`).
- Seeds all initial Lux Glazing UK data (Admin user, realistic UK glazing leads, B2B trade prospects, sample quotations with UK 20% VAT, SEO keywords, Google Ads metrics).
- Creates symlinks (`storage:link`) and clears all caches (`optimize:clear`).
- **Security:** It does **NOT** expose your database password or sensitive credentials anywhere on screen. It displays a luxury-styled confirmation page with direct access to the CRM.

### Pre-Seeded Admin Credentials:
- **Admin Dashboard URL:** `https://yourdomain.com/admin/dashboard` (or `/crm`)
- **Email:** `admin@luxglazing.co.uk`
- **Password:** `LuxGlazing@2026`

---

## 2. Core Modules Built Inside the Laravel Application

1. **Dashboard & Analytics:** Live tracking of leads, Hot priority leads, pipeline value in GBP (£), won contracts, and trade partners.
2. **Lead Management:** Complete listing of customer enquiries with service type, budget, timeframe, and stages.
3. **Pipeline Kanban Board:** 6 visual stages: *New Lead → Contacted → Qualified → Survey Booked → Quote Sent → Won*.
4. **AI Lead Scoring:** Evaluates project viability, budget tier, urgency, and recommended sales actions (Hot/Warm/Cold).
5. **B2B Trade Prospect Database:** Targeted directory of RIBA architects, luxury homebuilders, and commercial contractors with automated AI pitch generator.
6. **AI Email Generator:** Creates tailored quote follow-ups, laser survey confirmations, and trade introductions using OpenAI.
7. **Quotation Management with UK 20% VAT:** Generates itemized proposals for Cortizo/Schüco/Aluk systems, calculating net, 20% UK VAT, and total amounts with print-ready view.
8. **SEO Performance Tracker:** Monitors high-intent UK keywords, current Google rankings, and target landing pages.
9. **Paid Media & PPC Tracking:** Google Ads and Meta Ads metrics (spend in £, impressions, clicks, CPL, conversions).
10. **Public Website & Interactive Estimator:** Public homepage (`/`) with a dynamic Glazing Cost Estimator and live OpenAI Lead Chatbot widget (`/api/chat/message`).
