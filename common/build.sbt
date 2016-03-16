val scalaVer = "2.11.7"
val akkaVersion = "2.4.2"

organization := "com.goodrain"
name := "realtime-message-common"
version := "0.0.1-SNAPSHOT"

scalaVersion := scalaVer
autoScalaLibrary := false

resolvers += "Typesafe Releases" at "http://repo.typesafe.com/typesafe/maven-releases/"

scalacOptions ++= Seq(
  "-encoding", "UTF-8",
  "-deprecation",
  "-unchecked",
  "-feature",
  "-language:postfixOps",
  "-target:jvm-1.8")

parallelExecution in ThisBuild := false

parallelExecution in Test := false

logBuffered in Test := false

//unmanagedBase := baseDirectory.value / "lib"

//assemblyJarName := s"$name-$version.jar"

libraryDependencies ++= 
  Seq(
    "com.typesafe.akka" % "akka-actor_2.11" % akkaVersion % Provided,
    "com.typesafe.akka" % "akka-cluster_2.11" % akkaVersion % Provided,
    "org.objenesis" % "objenesis" % "2.2" % Provided,
    "de.ruedigermoeller" % "fst" % "2.45" % Provided,
    "com.google.guava" % "guava" % "19.0" % Provided,
    "com.fasterxml.jackson.core" % "jackson-databind" % "2.7.2" % Provided,
    "com.fasterxml.jackson.core" % "jackson-core" % "2.7.2" % Provided,
    "com.fasterxml.jackson.core" % "jackson-annotations" % "2.7.2" % Provided,
    "org.codehaus.jackson" % "jackson-mapper-asl" % "1.9.13",
    "com.fasterxml.jackson.datatype" % "jackson-datatype-guava" % "2.7.2" % Provided,
    "com.fasterxml.jackson.datatype" % "jackson-datatype-joda" % "2.7.2" % Provided,
    "com.fasterxml.jackson.module" % "jackson-module-jsonSchema" % "2.7.2" % Provided,
    "com.fasterxml.jackson.module" % "jackson-module-paranamer" % "2.7.2" % Provided,
    "com.fasterxml.jackson.module" % "jackson-module-scala_2.11" % "2.7.2" % Provided,
    "org.scala-lang.modules" % "scala-parser-combinators_2.11" % "1.0.4" % Provided,
    "org.codehaus.jackson" % "jackson-mapper-asl" % "1.9.13" % Provided,
    "io.netty" % "netty" % "3.10.5.Final" % Provided
  )