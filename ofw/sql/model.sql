/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

CREATE TABLE `cinema` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada cine',
  `id_user` INT(11) NOT NULL COMMENT 'Id del usuario que añade el cine',
  `name` VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Nombre del cine',
  `slug` VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Slug del nombre del cine',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `movie` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada película',
  `id_user` INT(11) NOT NULL COMMENT 'Id del usuario que añade la película',
  `id_cinema` INT(11) NOT NULL COMMENT 'Id del cine en el que un usuario ha visto la película',
  `name` VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Nombre de la película',
  `slug` VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Slug del nombre de la película',
  `ext` VARCHAR(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Extensión del archivo de la entrada',
  `imdb_url` VARCHAR(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Url de la película en IMDB',
  `cover_ext` VARCHAR(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Extensión del archivo de la carátula',
  `movie_date` DATETIME NOT NULL COMMENT 'Fecha en la que un usuario fue a ver la película',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada usuario',
  `name` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Nombre de usuario',
  `pass` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Contraseña cifrada del usuario',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `cinema`
  ADD KEY `fk_cinema_user_idx` (`id_user`),
  ADD CONSTRAINT `fk_cinema_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `movie`
  ADD KEY `fk_movie_user_idx` (`id_user`),
  ADD KEY `fk_movie_cinema_idx` (`id_cinema`),
  ADD CONSTRAINT `fk_movie_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_movie_cinema` FOREIGN KEY (`id_cinema`) REFERENCES `cinema` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
