**Base de datos: PixelKeyTrade**

**1.Tabla: Usuarios**

- `ID_Usuario` (Clave primaria, int)
- `Nombre` (varchar)
- `Apellido` (varchar)
- `CorreoElectronico` (varchar)
- `Contraseña` (varchar o hashed, dependiendo de la implementación)
- `TipoUsuario` (varchar)

2. **Tabla: Administradores**

   - `ID_Administrador` (Clave primaria, int)
   - `ID_Usuario` (Clave foránea que hace referencia a la tabla Usuarios)
   - `Rol` (varchar)

3. **Tabla: Productos**

   - `ID_Producto` (Clave primaria, int)
   - `Nombre` (varchar)
   - `Descripcion` (text)
   - `Precio` (decimal)
   - `Stock` (int)
   - `ID_Vendedor` (Clave foránea que hace referencia a la tabla Usuarios)
   - `Desarrollador` (varchar)

4. **Tabla: Categorías**

   - `ID_Categoria` (Clave primaria, int)
   - `Nombre` (varchar)

5. **Tabla: Producto_Categoria**

   - `ID_Producto_Categoria` (Clave primaria, int)
   - `ID_Producto` (Clave foránea que hace referencia a la tabla Productos)
   - `ID_Categoria` (Clave foránea que hace referencia a la tabla Categorías)

6. **Tabla: Descuentos**

   - `ID_Descuento` (Clave primaria, int)
   - `ID_Producto` (Clave foránea que hace referencia a la tabla Productos)
   - `PorcentajeDescuento` (decimal)

7. **Tabla: CarritoCompras**

   - `ID_Carrito` (Clave primaria, int)
   - `ID_Usuario` (Clave foránea que hace referencia a la tabla Usuarios)
   - `FechaCreacion` (datetime)
   - `Estado` (varchar)

8. **Tabla: DetalleCarrito**

   - `ID_DetalleCarrito` (Clave primaria, int)
   - `ID_Carrito` (Clave foránea que hace referencia a la tabla CarritoCompras)
   - `ID_Producto` (Clave foránea que hace referencia a la tabla Productos)
   - `Cantidad` (int)
   - `PrecioUnitario` (decimal)

9. **Tabla: Bitacora**

   - `ID_Bitacora` (Clave primaria, int)
   - `ID_Producto` (Clave foránea que hace referencia a la tabla Productos)
   - `Accion` (varchar)
   - `FechaAccion` (datetime)

**1. Crear la Base de Datos:**

```sql
CREATE DATABASE IF NOT EXISTS PixelKeyTrade;
USE PixelKeyTrade;
```

**2. Crear las Tablas:**

```sql
-- Tabla Usuarios
CREATE TABLE Usuarios (
    ID_Usuario INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(255),
    Apellido VARCHAR(255),
    CorreoElectronico VARCHAR(255),
    Contraseña VARCHAR(255),
    TipoUsuario VARCHAR(50)
);

-- Tabla Administradores
CREATE TABLE Administradores (
    ID_Administrador INT PRIMARY KEY AUTO_INCREMENT,
    ID_Usuario INT,
    Rol VARCHAR(50),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuario)
);

-- Tabla Productos
CREATE TABLE Productos (
    ID_Producto INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(255),
    Descripcion TEXT,
    Precio DECIMAL(10, 2),
    Stock INT,
    ID_Vendedor INT,
    Desarrollador VARCHAR(255),
    FOREIGN KEY (ID_Vendedor) REFERENCES Usuarios(ID_Usuario)
);

-- Tabla Categorías
CREATE TABLE Categorias (
    ID_Categoria INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(255)
);

-- Tabla Producto_Categoria (Tabla de relación muchos a muchos)
CREATE TABLE Producto_Categoria (
    ID_Producto_Categoria INT PRIMARY KEY AUTO_INCREMENT,
    ID_Producto INT,
    ID_Categoria INT,
    FOREIGN KEY (ID_Producto) REFERENCES Productos(ID_Producto),
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria)
);

-- Tabla Descuentos
CREATE TABLE Descuentos (
    ID_Descuento INT PRIMARY KEY AUTO_INCREMENT,
    ID_Producto INT,
    PorcentajeDescuento DECIMAL(5, 2),
    FOREIGN KEY (ID_Producto) REFERENCES Productos(ID_Producto)
);

-- Tabla CarritoCompras
CREATE TABLE CarritoCompras (
    ID_Carrito INT PRIMARY KEY AUTO_INCREMENT,
    ID_Usuario INT,
    FechaCreacion DATETIME,
    Estado VARCHAR(50),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuario)
);

-- Tabla DetalleCarrito
CREATE TABLE DetalleCarrito (
    ID_DetalleCarrito INT PRIMARY KEY AUTO_INCREMENT,
    ID_Carrito INT,
    ID_Producto INT,
    Cantidad INT,
    PrecioUnitario DECIMAL(10, 2),
    FOREIGN KEY (ID_Carrito) REFERENCES CarritoCompras(ID_Carrito),
    FOREIGN KEY (ID_Producto) REFERENCES Productos(ID_Producto)
);

-- Tabla Bitacora
CREATE TABLE Bitacora (
    ID_Bitacora INT PRIMARY KEY AUTO_INCREMENT,
    ID_Usuario INT,
    Accion VARCHAR(255),
    FechaAccion DATETIME,
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuario)
);
```

**3. Crear Triggers:**

```sql
-- Trigger para la tabla Bitacora (cambios en Productos)
DELIMITER //
CREATE TRIGGER trg_Bitacora_Productos
AFTER UPDATE ON Productos
FOR EACH ROW
BEGIN
    INSERT INTO Bitacora (ID_Usuario, Accion, FechaAccion)
    VALUES (NEW.ID_Vendedor, 'Actualización en Producto', NOW());
END;
//
DELIMITER ;

-- Trigger para la tabla Bitacora (cambios en Usuarios)
DELIMITER //
CREATE TRIGGER trg_Bitacora_Usuarios
AFTER UPDATE ON Usuarios
FOR EACH ROW
BEGIN
    IF NEW.Nombre != OLD.Nombre THEN
        INSERT INTO Bitacora (ID_Usuario, Accion, FechaAccion)
        VALUES (NEW.ID_Usuario, 'Actualización de Nombre de Usuario', NOW());
    END IF;

    IF NEW.CorreoElectronico != OLD.CorreoElectronico THEN
        INSERT INTO Bitacora (ID_Usuario, Accion, FechaAccion)
        VALUES (NEW.ID_Usuario, 'Actualización de Correo Electrónico', NOW());
    END IF;
END;
//
DELIMITER ;
```

**1. Insertar Datos:**

```sql
-- Insertar un usuario
INSERT INTO Usuarios (Nombre, Apellido, CorreoElectronico, Contraseña, TipoUsuario)
VALUES ('Juan', 'Pérez', 'juan@example.com', 'contraseña123', 'Cliente');

-- Insertar un administrador asociado a un usuario existente
INSERT INTO Administradores (ID_Usuario, Rol)
VALUES (1, 'Superadministrador');

-- Insertar un producto asociado a un vendedor existente
INSERT INTO Productos (Nombre, Descripcion, Precio, Stock, ID_Vendedor, Desarrollador)
VALUES ('Juego A', 'Descripción del juego A', 29.99, 100, 1, 'Desarrollador A');

-- Asignar categorías a un producto
INSERT INTO Categorias (Nombre) VALUES ('Acción'), ('Aventura');

INSERT INTO Producto_Categoria (ID_Producto, ID_Categoria) VALUES (1, 1), (1, 2);

-- Insertar un descuento asociado a un producto existente
INSERT INTO Descuentos (ID_Producto, PorcentajeDescuento)
VALUES (1, 10.00);

-- Crear un carrito de compras para un usuario
INSERT INTO CarritoCompras (ID_Usuario, FechaCreacion, Estado)
VALUES (1, NOW(), 'En proceso');

-- Agregar productos al carrito de compras
INSERT INTO DetalleCarrito (ID_Carrito, ID_Producto, Cantidad, PrecioUnitario)
VALUES (1, 1, 2, 29.99);

-- Insertar una entrada en la Bitacora
INSERT INTO Bitacora (ID_Usuario, Accion, FechaAccion)
VALUES (1, 'Creación de Producto', NOW());
```

**2. Actualizar Datos:**

```sql
-- Actualizar información de un usuario
UPDATE Usuarios SET Nombre = 'Juanito', Apellido = 'Pérez Pérez'
WHERE ID_Usuario = 1;

-- Actualizar el rol de un administrador
UPDATE Administradores SET Rol = 'Administrador de Ventas'
WHERE ID_Administrador = 1;

-- Actualizar información de un producto
UPDATE Productos SET Descripcion = 'Nueva descripción', Precio = 39.99
WHERE ID_Producto = 1;

-- Actualizar el estado de un carrito de compras
UPDATE CarritoCompras SET Estado = 'Completado'
WHERE ID_Carrito = 1;

-- Actualizar el descuento de un producto
UPDATE Descuentos SET PorcentajeDescuento = 15.00
WHERE ID_Descuento = 1;

-- Actualizar la cantidad de productos en el carrito de compras
UPDATE DetalleCarrito SET Cantidad = 3
WHERE ID_DetalleCarrito = 1;
```

**3. Eliminar Datos:**

```sql
-- Eliminar un descuento asociado a un producto
DELETE FROM Descuentos WHERE ID_Descuento = 1;

-- Eliminar un producto y sus asociaciones (categorías y registros en el carrito)
DELETE FROM Producto_Categoria WHERE ID_Producto = 1;
DELETE FROM DetalleCarrito WHERE ID_Producto = 1;
DELETE FROM Productos WHERE ID_Producto = 1;

-- Eliminar una categoría y sus asociaciones
DELETE FROM Producto_Categoria WHERE ID_Categoria = 1;
DELETE FROM Categorias WHERE ID_Categoria = 1;

-- Eliminar un usuario y sus asociaciones (administrador, carrito, registros en la bitácora)
DELETE FROM Administradores WHERE ID_Usuario = 1;
DELETE FROM CarritoCompras WHERE ID_Usuario = 1;
DELETE FROM Bitacora WHERE ID_Usuario = 1;
DELETE FROM Usuarios WHERE ID_Usuario = 1;
```
