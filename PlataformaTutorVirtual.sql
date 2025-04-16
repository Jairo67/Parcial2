-- Crear la base de datos
CREATE DATABASE PlataformaTutorVirtual;
GO
USE PlataformaTutorVirtual;
GO

-- Crear la tabla Usuarios
CREATE TABLE Usuarios (
    id_usuario INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(100) NOT NULL,
    correo NVARCHAR(100) NOT NULL UNIQUE,
    contraseña NVARCHAR(255) NOT NULL,
    rol NVARCHAR(50) NOT NULL CHECK (rol IN ('estudiante', 'tutor'))
);

-- Crear la tabla Estudiantes
CREATE TABLE Estudiantes (
    id_estudiante INT PRIMARY KEY,
    id_usuario INT FOREIGN KEY REFERENCES Usuarios(id_usuario),
    carrera NVARCHAR(100),
    nivel_academico NVARCHAR(50)
);

-- Crear la tabla Tutores
CREATE TABLE Tutores (
    id_tutor INT PRIMARY KEY,
    id_usuario INT FOREIGN KEY REFERENCES Usuarios(id_usuario),
    especialidad NVARCHAR(100),
    disponibilidad NVARCHAR(100)
);

-- Crear la tabla Interacciones
CREATE TABLE Interacciones (
    id_interaccion INT PRIMARY KEY IDENTITY(1,1),
    id_estudiante INT FOREIGN KEY REFERENCES Estudiantes(id_estudiante),
    id_tutor INT FOREIGN KEY REFERENCES Tutores(id_tutor),
    tipo_interaccion NVARCHAR(50) NOT NULL CHECK (tipo_interaccion IN ('IA', 'humano')),
    fecha_hora DATETIME NOT NULL DEFAULT GETDATE()
);

-- Crear la tabla Historial de Mensajes
CREATE TABLE HistorialMensajes (
    id_mensaje INT PRIMARY KEY IDENTITY(1,1),
    id_interaccion INT FOREIGN KEY REFERENCES Interacciones(id_interaccion),
    remitente NVARCHAR(50) NOT NULL CHECK (remitente IN ('estudiante', 'tutor', 'IA')),
    contenido NVARCHAR(MAX) NOT NULL,
    fecha_hora DATETIME NOT NULL DEFAULT GETDATE()
);

CREATE TABLE Preguntas (
    id_pregunta INT PRIMARY KEY IDENTITY(1,1),
    id_usuario INT FOREIGN KEY REFERENCES Usuarios(id_usuario), -- Estudiante que realiza la pregunta
    id_tutor INT FOREIGN KEY REFERENCES Usuarios(id_usuario),   -- Tutor que responderá, si aplica
    tipo_tutor NVARCHAR(50) NOT NULL CHECK (tipo_tutor IN ('IA', 'humano')), -- Diferencia entre IA y humano
    pregunta NVARCHAR(MAX) NOT NULL, -- Contenido de la pregunta
    fecha_pregunta DATETIME NOT NULL DEFAULT GETDATE() -- Fecha y hora de la pregunta
);

CREATE TABLE Respuestas (
    id_respuesta INT PRIMARY KEY IDENTITY(1,1),
    id_pregunta INT FOREIGN KEY REFERENCES Preguntas(id_pregunta), -- Relación con la pregunta correspondiente
    id_tutor INT FOREIGN KEY REFERENCES Usuarios(id_usuario), -- Tutor que proporciona la respuesta
    contenido_respuesta NVARCHAR(MAX) NOT NULL, -- Contenido de la respuesta
    fecha_respuesta DATETIME NOT NULL DEFAULT GETDATE() -- Fecha y hora en que se dio la respuesta
);

CREATE TABLE Mensajes (
    id_mensaje INT PRIMARY KEY IDENTITY(1,1),
    id_pregunta INT FOREIGN KEY REFERENCES Preguntas(id_pregunta), -- Relación con la pregunta
    id_remitente INT FOREIGN KEY REFERENCES Usuarios(id_usuario), -- Quién envió el mensaje
    id_destinatario INT FOREIGN KEY REFERENCES Usuarios(id_usuario), -- Quién lo recibe
    contenido NVARCHAR(MAX) NOT NULL, -- Mensaje enviado
    fecha DATETIME NOT NULL DEFAULT GETDATE() -- Fecha y hora del mensaje
);

SELECT * FROM Mensajes;

ALTER TABLE Mensajes ADD mensaje NVARCHAR(MAX);

SELECT * FROM Mensajes;

SELECT * FROM Preguntas WHERE id_pregunta = 1;

SELECT * FROM Mensajes WHERE id_pregunta = 1;

INSERT INTO Mensajes (id_pregunta, id_remitente, id_destinatario, mensaje) VALUES (1, 2, 3, 'Hola, ¿cómo estás?');

INSERT INTO Mensajes (id_pregunta, id_remitente, id_destinatario, mensaje) VALUES (1, 2, 3, 'Este es un mensaje de prueba.');

sp_columns Mensajes;

INSERT INTO Mensajes (id_pregunta, id_remitente, id_destinatario, mensaje) 
VALUES (1, 2, 3, 'Este es un mensaje de prueba.');

ALTER TABLE Mensajes DROP COLUMN contenido;

sp_columns Mensajes;

INSERT INTO Mensajes (id_pregunta, id_remitente, id_destinatario, mensaje) 
VALUES (1, 2, 3, 'Este es un mensaje de prueba.');

INSERT INTO Mensajes (id_pregunta, id_remitente, id_destinatario, mensaje) 
VALUES (1, 2, 3, 'Mensaje de prueba.');