# Code Review - Coding Standards Check

Review all staged/uncommitted changes against the project coding standards defined in CLAUDE.md.

## Instructions

1. **Get the list of changed files:**
```bash
git diff --name-only HEAD
git diff --staged --name-only
```

2. **For each changed PHP file, verify:**

### Variable Alignment
- Related variables should have aligned `=` signs:
```php
// CORRECT
$username       = getenv('TEST_USERNAME');
$token          = getenv('TEST_TOKEN');
$casinoGameCode = getenv('TEST_CASINO_GAME_CODE');

// INCORRECT
$username = getenv('TEST_USERNAME');
$token = getenv('TEST_TOKEN');
```

### Test Trait Structure (for tests/Traits/*.php)
- File naming: `S{XX}{ScenarioName}Scenario.php`
- Method naming: snake_case starting with action (`bet_`, `result_`, `login_`)
- Round code naming: snake_case descriptive (`'regular_gameround_scenario_1'`)
- Allure attributes present: `#[ParentSuite]`, `#[Suite]`, `#[DisplayName]`, `#[Description]`, `#[Test]`
- Proper step sequence:
  1. Get round code
  2. Get environment variables (aligned)
  3. Generate date
  4. Build payload
  5. Get endpoint
  6. Build full URL
  7. Execute in Allure::runStep
  8. Initialize checks array
  9. Attach request/response
  10. Run assertions
  11. Attach validation summary

### Payload Structure
- Double-quoted keys (JSON-style): `"requestId"` not `'requestId'`
- Consistent field ordering

### Import Statements
- Grouped and sorted:
  1. PHP built-in (DateTime, Exception)
  2. Third-party (GuzzleHttp, Qameta\Allure)
  3. Internal (Tests\Config, App\Models)

### Comments
- Use `// ` with space after
- Numbered steps in test methods

3. **Generate a report with:**
- List of files reviewed
- Issues found (if any) with line numbers
- Suggestions for fixes
- Overall pass/fail status

4. **Output format:**
```
## Code Review Report

### Files Reviewed
- [filename1]
- [filename2]

### Issues Found
#### [filename]:line
- Issue: [description]
- Expected: [correct pattern]
- Found: [actual pattern]

### Summary
- Total files: X
- Issues: Y
- Status: PASS/FAIL

### Recommendation
[If FAIL: List specific fixes needed before pushing]
[If PASS: Ready to push]
```

5. **If all checks pass**, confirm the code is ready for `/git-push`

6. **If issues found**, list specific fixes needed and offer to auto-fix them.
