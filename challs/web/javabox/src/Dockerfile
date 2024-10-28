FROM public.ecr.aws/docker/library/gradle:7.5.1-jdk17 AS build

WORKDIR /home/gradle/project
COPY build.gradle settings.gradle gradle.lockfile /home/gradle/project/
VOLUME /home/gradle/project/gradle.lockfile
COPY src /home/gradle/project/src
RUN gradle build --no-daemon -x test

FROM public.ecr.aws/docker/library/openjdk:17-slim

COPY --from=build /home/gradle/project/build/libs/*.jar /app/app.jar
COPY flag.txt /flag.txt
RUN chmod 444 /flag.txt

RUN adduser --system --no-create-home tempuser
USER tempuser
EXPOSE 20001

ENTRYPOINT ["java", "-jar", "/app/app.jar"]
