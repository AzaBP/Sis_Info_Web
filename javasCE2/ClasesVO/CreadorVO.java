package javasCE2.ClasesVO;

public class CreadorVO extends UsuarioVO {
    private static final long serialVersionUID = 1L;

    private String biografia;
    private int numeroSeguidores;

    public CreadorVO(){}

    public CreadorVO( String nombreId, String nombre, String correo, String password, String telefono, String codigoSuscripcion,
                      String biografia, int numeroSeguidores) {
        super(nombreId, nombre, correo, password, telefono, codigoSuscripcion);
        this.biografia = biografia;
        this.numeroSeguidores = numeroSeguidores;
    }

    public String getBiografia() {
        return biografia;
    }

    public void setBiografia(String biografia) {
        this.biografia = biografia;
    }

    public int getNumeroSeguidores() {
        return numeroSeguidores;
    }

    public void setNumeroSeguidores(int numeroSeguidores) {
        this.numeroSeguidores = numeroSeguidores;
    }

    @Override
    public String toString() {
        return "CreadorVO{" +
                "usuarioId='" + getNombreId() + '\'' +
                ", nombre='" + getNombre() + '\'' +
                ", correo='" + getCorreo() + '\'' +
                ", telefono='" + getTelefono() + '\'' +
                ", codigoSuscripcion='" + getCodigoSuscripcion() + '\'' +
                ", biografia='" + biografia + '\'' +
                ", numeroSeguidores=" + numeroSeguidores +
                '}';
    }
}
