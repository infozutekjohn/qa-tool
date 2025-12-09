# Run Automated API Tests

Run the automated API tests with the pre-configured test credentials.

Execute the following curl command to trigger the test run:

```bash
curl -X POST "http://127.0.0.1:8000/api/test-runs" -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"username\":\"HH88_K476502\",\"token\":\"HH88_Ym80ODhGRGpHdThseElwSlN0eTVLQT09Ojp6WElCTWpNM3VIcFlTUTQ2REtZRkpvT3VxOUVBVm9JM2EyZEtMNFRvOFIwPQ\",\"endpoint\":\"https://api-uat.agmidway.net\",\"casinoGameCode\":\"gpas_gstorm2_pop\",\"liveGameCode\":\"ubal\",\"crossGameCode\":\"\",\"launchAlias\":\"bal_baccaratko\",\"betPrimary\":\"1\",\"betSecondary\":\"1\",\"winPrimary\":\"2\",\"remoteBonusCodePrimary\":\"1234\",\"bonusInstanceCodePrimary\":\"1234\",\"bonusTemplatePrimary\":\"1234\",\"remoteBonusCodeSecondary\":\"1234\",\"bonusInstanceCodeSecondary\":\"1234\",\"bonusTemplateSecondary\":\"1234\",\"jackpot\":\"Yes\"}"
```

After running, report the test results including:
- Test ID
- PHPUnit exit code (0 = all passed, 1 = some failed)
- Report URL for viewing in the browser