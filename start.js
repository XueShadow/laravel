#!/usr/bin/env node

const { spawn } = require('child_process');

// Run migrations and seeders first
const migrations = spawn('php', ['artisan', 'migrate', '--force'], { stdio: 'inherit' });

migrations.on('close', (code) => {
  if (code !== 0) {
    console.error('Migration failed');
    process.exit(1);
  }

  // Run seeders
  const seeder = spawn('php', ['artisan', 'seed', '--force'], { stdio: 'inherit' });

  seeder.on('close', (code) => {
    if (code !== 0) {
      console.error('Seeding failed');
      process.exit(1);
    }

    // Start PHP server
    console.log('Starting PHP development server on 0.0.0.0:10000...');
    const server = spawn('php', ['-S', '0.0.0.0:10000', '-t', 'public/'], { stdio: 'inherit' });

    server.on('error', (err) => {
      console.error('Failed to start server:', err);
      process.exit(1);
    });
  });
});
