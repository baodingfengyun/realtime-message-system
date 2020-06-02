val scalaVer = "2.11.7"
val akkaVersion = "2.4.20"

organization := "com.goodrain"
name := "realtime-message-push"
version := "0.0.1-SNAPSHOT"

scalaVersion := scalaVer
autoScalaLibrary := false

resolvers += "Typesafe Releases" at "https://repo.typesafe.com/typesafe/maven-releases/"

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

unmanagedBase := baseDirectory.value / "project/lib"

//assemblyJarName := s"$name-$version.jar"

libraryDependencies ++= 
  Seq(
    "com.typesafe.akka" % "akka-actor_2.11" % akkaVersion % Provided,
    "com.typesafe.akka" % "akka-cluster_2.11" % akkaVersion % Provided,
    "com.typesafe.akka" % "akka-cluster-tools_2.11" % akkaVersion % Provided,
    "com.typesafe.akka" % "akka-contrib_2.11" % akkaVersion % Provided,
    "com.typesafe.akka" % "akka-http-core-experimental_2.11" % "2.0.3" % Provided,
    "com.typesafe.akka" % "akka-parsing-experimental_2.11" % "2.0.3" % Provided,
    "com.github.romix.akka" % "akka-kryo-serialization_2.11" % "0.4.0" % Provided,
    "com.esotericsoftware" % "kryo" % "3.0.3" % Provided,
    "com.typesafe.akka" % "akka-protobuf_2.11" % akkaVersion % Provided,
    "com.typesafe.akka" % "akka-remote_2.11" % akkaVersion % Provided,
    "com.typesafe.akka" % "akka-slf4j_2.11" % akkaVersion % Provided,
    "com.typesafe.akka" % "akka-stream-experimental_2.11" % "2.0.3" % Provided,
    "org.mongodb" % "casbah-commons_2.11" % "3.1.1" % Provided,
    "org.mongodb" % "casbah-core_2.11" % "3.1.1" % Provided,
    "org.mongodb" % "casbah-query_2.11" % "3.1.1" % Provided,
    "commons-lang" % "commons-lang" % "2.6" % Provided,
    "commons-logging" % "commons-logging" % "1.2" % Provided,
    "commons-pool" % "commons-pool" % "1.6" % Provided,
    "com.googlecode.concurrentlinkedhashmap" % "concurrentlinkedhashmap-lru" % "1.4.2" % Provided,
    "com.typesafe" % "config" % "1.3.0" % Provided,
    "de.ruedigermoeller" % "fst" % "2.45" % Provided,
    "com.google.guava" % "guava" % "19.0" % Provided,
    "org.scala-lang.modules" % "scala-parser-combinators_2.11" % "1.0.4" % Provided,
    "com.fasterxml.jackson.core" % "jackson-databind" % "2.7.2" % Provided,
    "com.fasterxml.jackson.core" % "jackson-core" % "2.7.2" % Provided,
    "com.fasterxml.jackson.core" % "jackson-annotations" % "2.7.2" % Provided,
    "com.fasterxml.jackson.datatype" % "jackson-datatype-guava" % "2.7.2" % Provided,
    "com.fasterxml.jackson.datatype" % "jackson-datatype-joda" % "2.7.2" % Provided,
    "com.fasterxml.jackson.module" % "jackson-module-jsonSchema" % "2.7.2" % Provided,
    "com.fasterxml.jackson.module" % "jackson-module-paranamer" % "2.7.2" % Provided,
    "com.fasterxml.jackson.module" % "jackson-module-scala_2.11" % "2.7.2" % Provided,
    "org.codehaus.jackson" % "jackson-mapper-asl" % "1.9.13" % Provided,
    "org.java-websocket" % "Java-WebSocket" % "1.3.0" % Provided,
    "com.google.code.findbugs" % "jsr305" % "3.0.1" % Provided,
    "log4j" % "log4j" % "1.2.17" % Provided,
    "io.dropwizard.metrics" % "metrics-core" % "3.1.2" % Provided,
    "nl.grons" % "metrics-scala_2.11" % "3.5.3" % Provided,
    "org.jvnet.mimepull" % "mimepull" % "1.9.6" % Provided,
    "com.esotericsoftware.minlog" % "minlog" % "1.2" % Provided,
    "org.mongodb" % "mongo-java-driver" % "2.12.4" % Provided,
    "io.netty" % "netty" % "3.10.5.Final" % Provided,
    "com.corundumstudio.socketio" % "netty-socketio" % "1.7.10" % Provided,
    "org.objenesis" % "objenesis" % "2.1" % Provided,
    "com.thoughtworks.paranamer" % "paranamer" % "2.8" % Provided,
    "org.parboiled" % "parboiled_2.11" % "2.1.2" % Provided,
    "org.parboiled" % "parboiled-core" % "1.1.7",
    "org.parboiled" % "parboiled-scala_2.11" % "1.1.7" % Provided,
    "com.google.protobuf" % "protobuf-java" % "2.6.1" % Provided,
    "org.reactivestreams" % "reactive-streams" % "1.0.0" % Provided,
    "org.scala-lang.modules" % "scala-parser-combinators_2.11" % "1.0.4" % Provided,
    "org.scala-lang.modules" % "scala-xml_2.11" % "1.0.5" % Provided,
    "com.chuusai" % "shapeless_2.11" % "2.3.0" % Provided,
    "org.typelevel" % "macro-compat_2.11" % "1.1.1" % Provided,
    "org.slf4j" % "slf4j-api" % "1.7.19" % Provided,
    "org.slf4j" % "slf4j-log4j12" % "1.7.19" % Provided,
    "com.typesafe" % "ssl-config-akka_2.11" % "0.1.3" % Provided,
    "com.typesafe" % "ssl-config-core_2.11" % "0.1.3" % Provided,
    "io.spray" % "spray-caching_2.11" % "1.3.3" % Provided,
    "io.spray" % "spray-can_2.11" % "1.3.3" % Provided,
    "io.spray" % "spray-http_2.11" % "1.3.3" % Provided,
    "io.spray" % "spray-httpx_2.11" % "1.3.3" % Provided,
    "io.spray" % "spray-io_2.11" % "1.3.3" % Provided,
    "io.spray" % "spray-json_2.11" % "1.3.1" % Provided,
    "io.spray" % "spray-routing-shapeless2_2.11" % "1.3.3" % Provided,
    "io.spray" % "spray-util_2.11" % "1.3.3" % Provided
    
  )