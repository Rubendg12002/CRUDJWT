<?php

require_once "conexion.php";

class Producto extends DB
{
    public function listar()
    {
        $sql = $this->conectar()->query("SELECT * FROM productos");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar($codigo, $producto, $precio, $cantidad)
    {
        $sql = $this->conectar()->prepare("INSERT INTO productos (codigo, producto, precio, cantidad) VALUES (?, ?, ?, ?)");
        return $sql->execute([$codigo, $producto, $precio, $cantidad]);
    }

    public function buscar($id)
    {
        $sql = $this->conectar()->prepare("SELECT * FROM productos WHERE id = ?");
        $sql->execute([$id]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $codigo, $producto, $precio, $cantidad)
    {
        $sql = $this->conectar()->prepare("UPDATE productos SET codigo = ?, producto = ?, precio = ?, cantidad = ? WHERE id = ?");
        return $sql->execute([$codigo, $producto, $precio, $cantidad, $id]);
    }

    public function eliminar($id)
    {
        $sql = $this->conectar()->prepare("DELETE FROM productos WHERE id = ?");
        return $sql->execute([$id]);
    }
}