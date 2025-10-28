package javasCE2.ClasesVO;
import java.io.Serializable;
import java.util.Objects;

public class CancionVO implements Serializable {
    private static final long serialVersionUID = 1L;

    private String nombre;
    private String nombreCreador;
    private String duracion;
    private Integer valoracion;

    public CancionVO(){}

    public CancionVO(String nombre, String nombreCreador, String duracion, Integer valoracion) {
        this.nombre = nombre;
        this.nombreCreador = nombreCreador;
        this.duracion = duracion;
        setValoracion(valoracion);
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getNombreCreador() {
        return nombreCreador;
    }

    public void setNombreCreador(String nombreCreador) {
        this.nombreCreador = nombreCreador;
    }

    public String getDuracion() {
        return duracion;
    }

    public void setDuracion(String duracion) {
        this.duracion = duracion;
    }

    public Integer getValoracion() {
        return valoracion;
    }

    public void setValoracion(Integer valoracion) {
        if (valoracion != null && valoracion >= 0 && valoracion <= 5) {
            this.valoracion = valoracion;
        } else {
            throw new IllegalArgumentException("La valoración debe estar entre 0 y 5.");
        }
    }

    @Override
    public String toString() {
        return "CancionVO{" +
                "nombre='" + nombre + '\'' +
                ", nombre creador='" + nombreCreador + '\'' +
                ", duracion='" + duracion + '\'' +
                ", valoracion=" + valoracion +
                '}';
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        CancionVO cancionVO = (CancionVO) o;
        return nombre != null && nombre.equals(cancionVO.nombre) &&
               nombreCreador != null && nombreCreador.equals(cancionVO.nombreCreador);
    }

    @Override
    public int hashCode() {
        return Objects.hash(nombre, nombreCreador);
    }
}
