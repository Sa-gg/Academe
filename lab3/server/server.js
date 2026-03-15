const fs = require('fs');
const path = require('path');
const express = require('express');

const envPath = path.join(__dirname, '.env');

if (fs.existsSync(envPath)) {
  const envLines = fs.readFileSync(envPath, 'utf8').split(/\r?\n/);

  envLines.forEach((line) => {
    const trimmed = line.trim();

    if (!trimmed || trimmed.startsWith('#')) {
      return;
    }

    const separatorIndex = trimmed.indexOf('=');

    if (separatorIndex === -1) {
      return;
    }

    const key = trimmed.slice(0, separatorIndex).trim();
    const value = trimmed.slice(separatorIndex + 1).trim();

    if (key && process.env[key] === undefined) {
      process.env[key] = value;
    }
  });
}

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
app.use(require('./middleware/validate'));
app.use('/api', require('./routes/orders'));

app.use((req, res) => {
  return res.status(404).json({
    error: 'NOT_FOUND',
    message: 'Route does not exist'
  });
});

app.use((err, req, res, next) => {
  return res.status(500).json({
    error: 'SERVER_ERROR',
    message: 'An unexpected error occurred'
  });
});

app.listen(PORT, () => {
  console.log(`Lab 3 API running on http://localhost:${PORT}`);
});