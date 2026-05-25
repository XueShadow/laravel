# 5000 Dummy Data Generation Guide

This document explains how to generate 5000 dummy records across all database tables for testing and development purposes.

## Overview

The application now includes optimized seeders that can generate:

| Table | Record Count | Details |
|-------|--------------|---------|
| Users (Students) | 5,000 | Full student profiles with courses and year levels |
| Students | 5,000 | Student details linked to users |
| Enrollments | 10,000-20,000 | 2-4 enrollments per student |
| Enrollment Subjects | 30,000-120,000 | 3-6 subjects per enrollment |
| Activity Logs | 40,000-100,000 | 8-20 logs per user (login, logout, updates, etc.) |
| OTPs | 0-25,000 | 0-5 OTP records per user |
| Subjects | 20 | Pre-defined subject list (Computer Science, IT, etc.) |

**Total Records: 95,000-270,000** (approximately)

## Prerequisites

1. Laravel development environment set up
2. Database configured and migrated
3. Administrator users created (run `php artisan db:seed` first if needed)

## Quick Start

### Generate All 5000 Dummy Records

```bash
cd c:\xampp\htdocs\pre-enrollment

# Run the DummyDataSeeder
php artisan db:seed --class=DummyDataSeeder
```

The seeder will:
1. Clear all existing dummy data (preserves admin accounts)
2. Create all subjects
3. Generate 5000 student users
4. Create enrollments with associated subjects
5. Generate activity logs for each user
6. Create OTP records

**Expected Duration:** 5-15 minutes depending on system performance

### Progress Output

You'll see progress messages like:
```
Starting dummy data generation...
Clearing existing dummy data...
Creating dummy subjects...
Creating dummy users and students...
Created 50 students...
Created 100 students...
...
Created 5000 students...
5000 dummy student users created successfully!
Generating enrollments for 5000 students...
Processed 500 students out of 5000...
...
```

## Individual Seeder Usage

You can also run individual seeders:

### Generate Only Users/Students
```bash
php artisan db:seed --class=DummyUserSeeder
```

### Generate Only Enrollments (requires students to exist)
```bash
php artisan db:seed --class=DummyEnrollmentSeeder
```

### Generate Only Activity Logs (requires users to exist)
```bash
php artisan db:seed --class=DummyActivityLogSeeder
```

### Generate Only OTP Records (requires users to exist)
```bash
php artisan db:seed --class=DummyOtpSeeder
```

## Data Structure

### Student Users
- 5000 randomly generated students
- Distributed across 5 courses:
  - Computer Science
  - Information Technology
  - Business Administration
  - Engineering
  - Nursing
- Year levels: 1-6
- Realistic email addresses and phone numbers
- Random addresses

### Enrollments
- Each student has 2-4 enrollment records
- Distributed across academic years:
  - 2024-2025
  - 2025-2026
  - 2026-2027
- Semesters: 1st, 2nd, summer
- Random statuses: pending, approved, rejected

### Activity Logs
- 8-20 logs per user
- Types: login, logout, profile_update, enrollment_submitted, password_reset, register
- Realistic IP addresses and timestamps

### OTP Records
- 0-5 OTP records per user
- 6-digit codes
- Random expiration dates
- Mix of used and unused codes

## Troubleshooting

### Out of Memory Error
If you get an out-of-memory error, the batch size is too large. Edit the seeders and reduce `$batchSize`:

In `DummyUserSeeder.php`, change:
```php
$batchSize = 50;  // Reduce to 25 or 10
```

### Duplicate Key Error
This shouldn't happen, but if it does, clear your database and run:
```bash
php artisan migrate:fresh
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=DummyDataSeeder
```

### Slow Performance
- Ensure your database server is running properly
- Close other applications consuming resources
- Consider running during off-peak times

## Resetting Data

To clear all dummy data and start over:

```bash
# Clear only dummy data (keeps admins)
php artisan db:seed --class=DummyDataSeeder

# Or completely reset and rebuild
php artisan migrate:fresh
php artisan db:seed
php artisan db:seed --class=DummyDataSeeder
```

## Testing with Dummy Data

Once you have 5000 dummy records, you can:

1. **Test Search/Filter Performance** - Filter 5000 students by course, year level, etc.
2. **Test Pagination** - Display and paginate through large datasets
3. **Test Reports** - Generate enrollment reports with large data volumes
4. **Test API Performance** - Load test APIs with realistic data volumes
5. **Bulk Operations** - Test bulk approval/rejection of enrollments
6. **Analytics** - Analyze activity patterns with historical logs

## Customizing Generated Data

To modify the generation parameters, edit the seeder files:

- `DummyUserSeeder.php` - Adjust courses, name pools, batch sizes
- `DummyEnrollmentSeeder.php` - Adjust enrollment counts per student, semesters
- `DummyActivityLogSeeder.php` - Adjust log types, date ranges
- `DummyOtpSeeder.php` - Adjust OTP counts per user
- `DummySubjectSeeder.php` - Add/remove subjects

Example: To generate 10,000 students instead of 5000:

In `DummyUserSeeder.php`, change:
```php
for ($batch = 0; $batch < 100; $batch++) {  // 100 batches × 50 = 5000
    // Change to:
    for ($batch = 0; $batch < 200; $batch++) {  // 200 batches × 50 = 10000
```

## Performance Notes

- Each seeder run processes data in batches to optimize database performance
- The seeders use direct database inserts for speed (not model creation)
- Timestamps are generated to simulate realistic historical data
- Duplicate enrollments are prevented using composite keys

## Support

If you encounter issues:
1. Check the Laravel log file: `storage/logs/laravel.log`
2. Ensure all migrations have been run: `php artisan migrate`
3. Verify database connection in `.env` file
4. Check available disk space and memory
