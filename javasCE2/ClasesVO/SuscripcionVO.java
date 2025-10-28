package javasCE2.ClasesVO;
import java.io.Serializable;


public class SuscripcionVO implements Serializable {
    private static final long serialVersionUID = 1L;

    private double precio;
    private String tipo;
    private String codigo;

    public SuscripcionVO(){}

    public SuscripcionVO(double precio, String tipo, String codigo) {
        this.precio = precio;
        this.tipo = tipo;
        this.codigo = codigo;
    }

    public double getPrecio() {
        return precio;
    }

    public void setPrecio(double precio) {
        this.precio = precio;
    }

    public String getTipo() {
        return tipo;
    }

    public void setTipo(String tipo) {
        this.tipo = tipo;
    }

    public String getCodigo() {
        return codigo;
    }

    public void setCodigo(String codigo) {
        this.codigo = codigo;
    }

     // Métodos auxiliares
    @Override
    public String toString() {
        return "SuscripcionVO{" +
                "codigo='" + codigo + '\'' +
                ", suscripcionTipo='" + tipo + '\'' +
                ", precio=" + precio +
                '}';
    }
    
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        SuscripcionVO that = (SuscripcionVO) o;
        return codigo != null && codigo.equals(that.codigo);
    }
    
    @Override
    public int hashCode() {
        return codigo != null ? codigo.hashCode() : 0;
    }
}
