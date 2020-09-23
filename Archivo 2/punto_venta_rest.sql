-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-09-2020 a las 11:13:57
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `punto_venta_rest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apertura_caja`
--

CREATE TABLE `apertura_caja` (
  `id_apertura` int(11) NOT NULL,
  `efectivo` float DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `abierto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `apertura_caja`
--

INSERT INTO `apertura_caja` (`id_apertura`, `efectivo`, `id_usuario`, `abierto`) VALUES
(1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `descripcion`) VALUES
(1, 'cat_gen');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `ci` int(11) NOT NULL,
  `telefono` text NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `ci`, `telefono`, `direccion`) VALUES
(1, 'Cliente gen', 1, '1', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id_gasto` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `monto` int(11) NOT NULL,
  `tipo` text NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `descripcion`, `fecha`, `monto`, `tipo`, `id_proveedor`) VALUES
(1, 'Producto de prueba', '2020-09-21 20:14:53', 899999, 'prueba1', 0),
(2, 'prueba 2', '2020-09-21 20:14:53', 98888, 'random', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `monedas`
--

CREATE TABLE `monedas` (
  `real_` int(11) NOT NULL,
  `dolar` int(11) NOT NULL,
  `peso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `vendido` int(11) NOT NULL DEFAULT 0,
  `guardado` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `cerrado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `id_usuario`, `total`, `vendido`, `guardado`, `fecha_creacion`, `fecha_modificacion`, `cerrado`) VALUES
(9, 1, 1, 1900000, 1, 1, '2020-07-21 10:25:05', '2020-07-21 14:31:47', 1),
(12, 1, 1, 200000, 1, 0, '2020-07-21 15:11:04', '2020-07-21 19:11:19', 1),
(17, 1, 1, 10500000, 1, 1, '2020-07-22 08:09:38', '2020-07-23 19:18:02', 1),
(19, 1, 1, 2400, 1, 0, '2020-07-24 09:26:46', '2020-07-24 16:59:30', 1),
(20, 1, 1, 1200, 1, 0, '2020-07-24 10:04:22', '2020-07-24 17:04:37', 1),
(21, 1, 1, 7000, 1, 1, '2020-08-03 10:55:01', '2020-08-10 18:54:48', 1),
(22, 1, 1, 3000000, 1, 0, '2020-08-10 11:51:38', '2020-08-10 18:55:57', 1),
(23, 1, 1, 7000, 1, 1, '2020-08-10 11:56:15', '2020-08-10 19:00:14', 1),
(24, 1, 1, 12000, 1, 0, '2020-08-10 11:57:48', '2020-08-10 19:00:02', 1),
(25, 1, 1, 12000, 1, 0, '2020-08-10 12:00:45', '2020-08-10 19:01:21', 1),
(28, 1, 1, 1500400, 1, 1, '2020-08-11 09:13:19', '2020-08-11 16:14:27', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_items`
--

CREATE TABLE `pedido_items` (
  `id_ped_item` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedido_items`
--

INSERT INTO `pedido_items` (`id_ped_item`, `id_producto`, `id_pedido`, `cantidad`) VALUES
(39, 113, 9, 2),
(40, 114, 9, 1),
(42, 113, 12, 1),
(49, 114, 17, 7),
(50, 98, 19, 12),
(51, 98, 20, 6),
(52, 101, 21, 1),
(53, 114, 22, 2),
(54, 101, 23, 1),
(56, 116, 24, 1),
(57, 116, 25, 1),
(60, 114, 28, 1),
(61, 107, 28, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `codigo` text NOT NULL,
  `descripcion` text NOT NULL,
  `stock` int(11) NOT NULL,
  `precio` int(11) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT 0,
  `ilimitado` int(11) NOT NULL DEFAULT 0,
  `precio_costo` int(11) NOT NULL,
  `precio_mayorista` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `id_proveedor`, `codigo`, `descripcion`, `stock`, `precio`, `borrado`, `ilimitado`, `precio_costo`, `precio_mayorista`) VALUES
(94, 1, 1, 'cod223', 'desc prueba', 20, 200, 1, 0, 80, 75),
(98, 1, 1, 'cod223', 'prueba', 0, 4999, 0, 0, 300, 350),
(99, 1, 1, '9090', 'Producto editado 2', 5, 60, 0, 0, 40, 50),
(100, 1, 1, 'cod121212121', 'Snack', 21, 3700, 1, 0, 0, 0),
(101, 1, 1, 'sku001', 'Jabón en polvo OMO', 0, 7000, 1, 0, 0, 0),
(105, 1, 1, '42314', 'Pepsi cola 1lt', 0, 4500, 1, 0, 1000, 0),
(106, 1, 1, 'ulala', 'Pepsi', 5, 900, 0, 0, 500, 600),
(107, 1, 1, '243234', 'Esponja', 1, 400, 0, 0, 150, 0),
(111, 1, 1, 'cod1', 'Placa madre', 1, 200000, 1, 1, 0, 0),
(112, 1, 1, 'cod2', 'Silla gamer', 68, 1500000, 1, 0, 0, 0),
(113, 1, 1, 'cod1', 'Placa madre', 18, 14000, 0, 0, 13000, 13500),
(114, 1, 1, 'cod2', 'Silla gamer', 35, 1500000, 0, 0, 0, 0),
(115, 1, 1, 'caj111', 'Caja de madera', 19, 5000, 0, 0, 200, 0),
(116, 1, 1, 'cod03', 'Bidon agua 5 litroS', 18, 12000, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `telefono` text NOT NULL,
  `empresa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre`, `telefono`, `empresa`) VALUES
(1, 'Proveedor Gen', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `permiso` int(11) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT 0,
  `usuario` text NOT NULL,
  `pass` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `permiso`, `borrado`, `usuario`, `pass`) VALUES
(1, 'Arsenio', 1, 0, 'arsenio.d.g.g@gmail.com', '202cb962ac59075b964b07152d234b70');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id_gasto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_cliente` (`id_cliente`),
  ADD KEY `fk_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD PRIMARY KEY (`id_ped_item`),
  ADD KEY `fk_producto` (`id_producto`),
  ADD KEY `fk_pedido` (`id_pedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_proveedores` (`id_proveedor`),
  ADD KEY `fk_cat_productos` (`id_categoria`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  MODIFY `id_ped_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD CONSTRAINT `fk_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_cat_productos` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `fk_proveedores` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
