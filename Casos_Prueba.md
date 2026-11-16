# Pruebas del proyecto Taskshield


## Caso de prueba 1: Introducir un email inválido

El sistema detecta que el email no tiene un formato válido y muestra un mensaje de error
![alt text](image.png)

Esto ocurre porque el código utiliza un filtro de validación de email, que comprueba que la dirección introducida cumple el formato esperado.

Por tanto, el formulario no inserta la incidencia en la base de datos.

---

## Caso de prueba 2: Editar una incidencia con un ID que no existe

Hemos puesto el id de incidencia 999 que obviamente no se encuentra en nuestra base de datos.

El sistema ha detectado que no existe ninguna incidencia con ese ID y nos muestra un mensaje de error.

Esto se consigue mediante una consulta preparada que busca la incidencia por su identificador:
![alt text](image-1.png)

---

## Caso de prueba 3: Fallo en la segunda parte de la transacción

En nuestro proyecto, al asignar una incidencia a un técnico se realizan varias operaciones:

1. Se actualiza la incidencia para asignarle el técnico y cambiar su estado a `En Proceso`.
2. Se actualiza el contador `tareas_activas` del técnico.

Estas operaciones están dentro de una transacción PDO.
Si la primera consulta UPDATE se ejecuta, pero la segunda falla porque la columna indicada no existe se producira un error, se ejecutará el bloque `catch` y se llamará al `rollback()`

Esta instrucción deshace los cambios realizados paara que no se guarden en nuestra bdd

Por tanto:

- La incidencia  conserva su `tecnico_id` anterior.
- Su estado debe seguir siendo el que tenía antes de la prueba.
- El contador `tareas_activas` del técnico no cambiará.