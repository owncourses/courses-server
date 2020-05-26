<p align="center">
  <a href="" rel="noopener">
 <img width=200px height=200px src="https://i.ibb.co/YQ52bDY/logo.png" alt="OwnCourses logo"></a>
</p>

<h3 align="center">OwnCourses server</h3>

<div align="center">

  [![Status](https://img.shields.io/badge/status-active-success.svg)]() 
  [![GitHub Issues](https://img.shields.io/github/issues/owncourses/courses-server.svg)](https://github.com/owncourses/courses-server/issues)
  [![GitHub Pull Requests](https://img.shields.io/github/issues-pr/owncourses/courses-server.svg)](https://github.com/owncourses/courses-server/pulls)
  [![License](https://img.shields.io/badge/license-MIT-blue.svg)](/LICENSE)
  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/owncourses/courses-server/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/owncourses/courses-server/?branch=master)
  [![CircleCI](https://circleci.com/gh/owncourses/courses-server/tree/master.svg?style=svg)](https://circleci.com/gh/owncourses/courses-server/tree/master) 
  [![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

</div>

### OwnCourses platform provides solution for independent online course makers.

---

OwnCourses allows You to create unlimited courses, define your course modules, and lessons and give access to them for Your students. 
This server application provides API used by student portal application (build in react).

All of that can be installed on Your own server for free.

## 📝 Table of Contents
- [What's done?](#mvp)
- [Next tasks](#next)
- [Development installation (with docker)](#docker)
- [JWT tokens configuration](#jwt)

## What's done? <a name = "mvp"></a>

- [x] Courses
  - [x] Course covers
  - [x] Course status (visible/hidden)
  - [x] Course start/end
  - [x] All course attachments
- [x] Module
- [x] Lessons
  - [x] Lesson completion API
  - [x] Lesson covers
  - [x] Lesson attachments
  - [x] Lesson duration
- [x] Basic admin panel
- [x] Users
  - [x] Login
  - [x] Password reset
  - [x] User courses
- [x] Module/Course progress based on completed lessons and duration 
- [x] Course authors
  - [x] Image, name, bio, courses
- [x] Settings 
  - [x] Emails settings
- [x] Notifications 
  - [x] Add new notification
  - [x] List notifications
    - [x] Mark already read notifications
  - [x] Mark notifications as read by user

## Next tasks <a name = "next"></a>

- [ ] Lesson homework tasks (mark as completed, left comments, interact with course author)
- [ ] Users
  - [ ] Users progress in course (finished lessons, time spend in platform)
  - [ ] Users questions about homework's


## Development installation (with docker) <a name = "docker"></a>

**Add owncourses.test to you `/etc/hosts`**
```bash
127.0.0.1       owncourses.test
```

**Run docker infrastructure**
```bash
cd  docker/
docker-compose build
docker-compose up -d
docker-compose exec app composer install
```

**Create local config file `.env.dev.local` (in project root directory - not /docker) with content:**
```bash
DATABASE_URL=pgsql://${POSTGRES_USER}:${POSTGRES_PASSWORD}@${POSTGRES_HOST}:${POSTGRES_POST}/${POSTGRES_DB}
``` 

**Create database and schema**
```bash
docker-compose exec app bin/console doctrine:migrations:migrate
```

**Create admin user**
```bash
docker-compose exec app bin/console app:user:create test@example.com testPassword Test User
docker-compose exec app bin/console app:user:promote test@example.com ROLE_ADMIN
```

Open `http://owncourses.test/admin` in Your browser


## JWT tokens configuration <a name = "jwt"></a>

#### Generate keys needed for token creation and verification

```bash
openssl genrsa -out config/jwt/private.pem -aes256 4096 # set this value as JWT_SECRET_KEY env variable
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem # set this value as JWT_PUBLIC_KEY env variable
```
