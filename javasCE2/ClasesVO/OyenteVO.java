package javasCE2.ClasesVO;

public class OyenteVO extends UsuarioVO {

    private static final long serialVersionUID = 1L;

    private String prferencias;
    private String historialReproduccion;

    public OyenteVO(){
        super();
    }

    public OyenteVO( String nombreId, String nombre, String correo, String password, String telefono, String codigoSuscripcion,
                     String prferencias, String historialReproduccion) {
        super(nombreId, nombre, correo, password, telefono, codigoSuscripcion);
        this.prferencias = prferencias;
        this.historialReproduccion = historialReproduccion;
    }

    public String getPrferencias() {
        return prferencias;
    }

    public void setPrferencias(String prferencias) {
        this.prferencias = prferencias;
    }

    public String getHistorialReproduccion() {
        return historialReproduccion;
    }

    public void setHistorialReproduccion(String historialReproduccion) {
        this.historialReproduccion = historialReproduccion;
    }

    @Override
    public String toString() {
        return "OyenteVO{" +
                "usuarioId='" + getNombreId() + '\'' +
                ", nombre='" + getNombre() + '\'' +
                ", correo='" + getCorreo() + '\'' +
                ", telefono='" + getTelefono() + '\'' +
                ", codigoSuscripcion='" + getCodigoSuscripcion() + '\'' +
                ", prferencias='" + prferencias + '\'' +
                ", historialReproduccion='" + historialReproduccion + '\'' +
                '}';
    }
}
