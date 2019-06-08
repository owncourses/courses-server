# OwnCourses back office server and API

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/owncourses/courses-server/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/owncourses/courses-server/?branch=master)
[![CircleCI](https://circleci.com/gh/owncourses/courses-server/tree/master.svg?style=svg)](https://circleci.com/gh/owncourses/courses-server/tree/master) 

### OwnCourses platform provides solution for independent online course makers.

OwnCourses allows You to create unlimited courses, define your course modules, and lessons and give access to them for Your students.
This server application provides API used by student portal application (build in react).

All of that can be installed on Your own server for free.

## MPV components

- [x] Courses
  - [x] Course covers
  - [x] Course status (visible/hidden)
  - [x] Course start/end
- [x] Module
- [x] Lessons
  - [x] Lesson completion API
  - [x] Lesson covers
  - [x] Lesson attachments
  - [x] Lesson duration
- [x] Basic admin panel
- [x] Users
  - [x] Login
  - [x] User courses
- [x] Module/Course progress based on completed lessons and duration 
- [x] Course authors
  - [x] Image, name, bio, courses

## Next tasks

- [ ] Lesson homework tasks (mark as completed, left comments, interact with course author)
- [ ] Users
  - [ ] Users progress in course (finished lessons, time spend in platform)
  - [ ] Users questions about homework's


### Development installation (with docker)

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


### JWT tokens

#### Generate keys needed for token creation and verification

```bash
openssl genrsa -out config/jwt/private.pem -aes256 4096 # set this value as JWT_SECRET_KEY env variable
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem # set this value as JWT_PUBLIC_KEY env variable
```
