package javasCE2.ClasesVO;
import java.io.Serializable;

public class UsuarioVO implements Serializable {
    private static final long serialVersionUID = 1L;

    private String nombreId;
    private String nombre;
    private String correo;
    private String password;
    private String telefono;
    private String codigoSuscripcion;

    public UsuarioVO(){}

    public UsuarioVO(String nombreId, String nombre, String correo, String password, String telefono, String codigoSuscripcion) {
        this.nombreId = nombreId;
        this.nombre = nombre;
        this.correo = correo;
        this.password = password;
        this.telefono = telefono;
        this.codigoSuscripcion = codigoSuscripcion;
    }
    public String getNombreId() {
        return nombreId;
    }
    public void setNombreId(String nombre_id) {
        this.nombreId = nombre_id;
    }
    public String getNombre() {
        return nombre;
    }
    public void setNombre(String nombre) {
        this.nombre = nombre;
    }
    public String getCorreo() {
        return correo;
    }
    public void setCorreo(String correo) {
        this.correo = correo;
    }
    public String getPassword() {
        return password;
    }
    public void setPassword(String password) {
        this.password = password;
    }
    public String getTelefono() {
        return telefono;
    }
    public void setTelefono(String telefono) {
        this.telefono = telefono;
    }
    public String getCodigoSuscripcion() {
        return codigoSuscripcion;
    }
    public void setCodigoSuscripcion(String codigoSuscripcion) {
        this.codigoSuscripcion = codigoSuscripcion;
    }

    // Métodos auxiliares
    @Override
    public String toString() {
        return "UsuarioVO{" +
                "nombreId='" + nombreId + '\'' +
                ", nombre='" + nombre + '\'' +
                ", telefono=" + telefono +
                ", correo='" + correo + '\'' +
                ", codigoSuscripcion='" + codigoSuscripcion + '\'' +
                '}';
    }

    // Para comprobar si dos usuarios son iguales basándonos en su nombreId
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        UsuarioVO usuario = (UsuarioVO) o;
        return nombreId != null && nombreId.equals(usuario.nombreId);
    }
    
    @Override
    public int hashCode() {
        return nombreId != null ? nombreId.hashCode() : 0;
    }
}

