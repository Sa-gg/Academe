module.exports = (req, res, next) => {
  if (req.method === 'POST' && !req.is('application/json')) {
    return res.status(400).json({
      error: 'INVALID_CONTENT_TYPE',
      message: 'Content-Type must be application/json'
    });
  }

  next();
};