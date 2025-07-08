# KKHS Ticket Scanner Stress Test Guide

## SAFETY FIRST

### Is This Safe?
YES, this stress test is completely safe because:

- Uses Test Data Only: All test tickets have "STRESS-TEST" prefix and won't affect real tickets  
- Isolated Testing: Creates separate test users and concerts  
- Easy Cleanup: All test data can be removed with one command  
- No Production Impact: Test data is clearly marked and separated  
- Rollback Protection: Database transactions ensure data integrity  

### Safety Measures Built-In:
- Test tickets use unique "KKHS-STRESS-TEST-" prefix
- Test users have "@test.kkhs.edu.my" email domain
- Test concerts clearly marked with "STRESS-TEST" in title
- Confirmation prompts before running tests
- Automatic cleanup functionality
- Production environment warnings

---

## Quick Start (3 Simple Steps)

### Step 1: Basic Test
```bash
# Open PowerShell in your project directory
cd D:\laragon\www\kkhs-concert-ticket-booking-system

# Run a small test first (100 tickets, 5 scanners)
php artisan ticket:stress-test --tickets=100 --scanners=5 --mode=full --confirm
```

### Step 2: Full Concert Day Simulation
```bash
# Run the full 2000 tickets with 10 scanners (concert day scenario)
php artisan ticket:stress-test --tickets=2000 --scanners=10 --mode=full --confirm
```

### Step 3: Cleanup (When Done)
```bash
# Clean up all test data
php artisan ticket:stress-test --mode=cleanup --confirm
```

---

## Simple Step-by-Step Instructions

### Before You Start
1. Make sure Laragon is running (MySQL and Apache should be active)
2. Your Laravel app should be working (visit http://localhost:8000 to check)
3. Database should be connected

### Step 1: Check Everything is Working
```bash
# Navigate to your project
cd D:\laragon\www\kkhs-concert-ticket-booking-system

# Check Laravel is working
php artisan --version

# Test database connection
php artisan migrate:status
```

### Step 2: Small Test First (Recommended)
```bash
# Start with a small test to verify everything works
php artisan ticket:stress-test --tickets=50 --scanners=3 --mode=full

# This will:
# - Create 50 test tickets
# - Simulate 3 concurrent scanners
# - Give you a performance report
# - Take about 1-2 minutes
```

### Step 3: Concert Day Test
```bash
# Full stress test simulating concert day
php artisan ticket:stress-test --tickets=2000 --scanners=10 --mode=full --delay=150

# What these options mean:
# --tickets=2000    : Simulate 2000 people
# --scanners=10     : 10 entrance scanners  
# --mode=full       : Create test data and run test
# --delay=150       : 150ms between scans (realistic timing)
```

### Step 4: Check Results
The test will show you:
- Throughput: How many scans per second
- Success Rate: Percentage of successful scans
- Deadlocks: Database problems (should be 0)
- Performance Assessment: Whether your system is ready

### Step 5: Cleanup
```bash
# Remove all test data (IMPORTANT: Run this when done)
php artisan ticket:stress-test --mode=cleanup
```

---

## Different Test Scenarios

### Normal Concert Day (Recommended)
```bash
php artisan ticket:stress-test --tickets=2000 --scanners=10 --delay=120
```

### Rush Scenario (Everyone arrives at once)
```bash
php artisan ticket:stress-test --tickets=2000 --scanners=15 --delay=50
```

### Conservative Test (Slower scanning)
```bash
php artisan ticket:stress-test --tickets=2000 --scanners=8 --delay=200
```

---

## Understanding Your Results

### Good Results Look Like:
- Success Rate: Above 99%
- Throughput: Above 30 scans per second
- Deadlocks: 0 (or very few)
- Average Scan Time: Less than 200ms
- Assessment: "EXCELLENT" or "GOOD"

### Warning Signs:
- Success Rate: Below 95%
- Deadlocks: More than 10
- Throughput: Below 20 scans per second
- Many errors in the output

### Performance Levels:
- Concert Ready: Above 50 scans/sec, above 99% success rate
- Acceptable: Above 30 scans/sec, above 95% success rate  
- Needs Work: Below 30 scans/sec, below 95% success rate

---

## Common Problems and Solutions

### "Command not found"
```bash
# Make sure you're in the right directory
cd D:\laragon\www\kkhs-concert-ticket-booking-system

# Check if the command exists
php artisan list | Select-String "stress"
```

### "Database connection error"
- Check Laragon is running
- Check your .env file database settings
- Try: php artisan config:cache

### "No test tickets found"
```bash
# Create test data first
php artisan ticket:stress-test --mode=seed --tickets=100
```

### Poor Performance Results
1. Check Task Manager for high CPU/Memory usage
2. Check disk space
3. Restart Laragon services
4. Clear Laravel caches:
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```

---

## Test Modes Explained

### --mode=seed
- Only creates test data
- Use when you want to prepare data for multiple test runs
- Safe to run multiple times

### --mode=scan  
- Only runs the scanning test
- Requires test data to already exist
- Use to repeat tests with the same data

### --mode=full
- Creates data and runs test (RECOMMENDED)
- Complete end-to-end test
- Best for first-time users

### --mode=cleanup
- Removes all test data
- Run when finished testing
- Frees up database space

---

## When to Run Tests

### 2 Weeks Before Concert:
- Run basic test: --tickets=100 --scanners=5
- Check for any major problems

### 1 Week Before Concert:
- Run full test: --tickets=2000 --scanners=10
- Test different scenarios
- Fix any issues

### 2 Days Before Concert:
- Final test run
- Make sure everything is working
- Train staff on scanner speeds

### Day of Concert:
- Quick system check
- Monitor real scanning if possible

---

## Success Criteria for Concert Day

Your system is ready if:
- Success rate above 95% consistently
- No deadlocks in multiple test runs
- Throughput above 30 scans per second total
- Average scan time below 200ms
- System stable during peak load tests

Estimated Time for 2000 People: 5-15 minutes with 10 scanners

---

## Emergency Backup Plan

### If Tests Show Poor Performance:

1. Immediate Actions:
   - Add more scanners on concert day
   - Make sure internet connection is stable
   - Have backup manual process ready

2. Technical Fixes:
   - Restart Laragon services
   - Clear Laravel caches
   - Check database optimization

3. Concert Day Backup:
   - Manual ticket verification process
   - Extra staff at entrances
   - Printed backup ticket lists

---

## Getting Help

If you have problems:

1. Check Laravel Logs:
   ```bash
   # Look at recent errors
   Get-Content storage\logs\laravel.log -Tail 50
   ```

2. Monitor System:
   ```bash
   php artisan ticket:monitor-scanning --real-time
   ```

3. Check System Info:
   ```bash
   php artisan about
   ```

Remember: The stress test uses only test data and is completely safe to run. 