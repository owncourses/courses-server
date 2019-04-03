# OwnCourses back office server and API

### OwnCourses platform provides solution for independent online course makers.

OwnCourses allows You to create unlimited courses, define your course modules, and lessons and give access to them for Your students.
This server application provides API used by student portal application (build in react).

All of that can be installed on Your own server for free.

## MPV components

- [x] Courses
  - [x] Course covers
- [x] Module
- [x] Lessons
- [x] Basic admin panel
- [x] Users
  - [x] Login
  - [x] User courses

## Next tasks

- [ ] Course status
- [ ] Course start date/time
- [ ] Lesson attachments
- [ ] Lesson covers
- [ ] Lesson homework tasks
- [ ] Lesson completion API


### Development instalation (with docker)

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
