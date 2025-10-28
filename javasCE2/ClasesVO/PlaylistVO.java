package javasCE2.ClasesVO;
import java.io.Serializable;
import java.util.Objects;

public class PlaylistVO implements Serializable {
    private static final long serialVersionUID = 1L;

    private String listaId;
    private String nombreCancion;
    private String nombreCreador;

    public PlaylistVO(){}

    public PlaylistVO(String listaId, String nombreCancion, String nombreCreador) {
        this.listaId = listaId;
        this.nombreCancion = nombreCancion;
        this.nombreCreador = nombreCreador;
    }

    public String getListaId() {
        return listaId;
    }

    public void setListaId(String listaId) {
        this.listaId = listaId;
    }

    public String getNombreCancion() {
        return nombreCancion;
    }

    public void setNombreCancion(String nombreCancion) {
        this.nombreCancion = nombreCancion;
    }

    public String getNombreCreador() {
        return nombreCreador;
    }

    public void setNombreCreador(String nombreCreador) {
        this.nombreCreador = nombreCreador;
    }

    @Override
    public String toString() {
        return "PlaylistVO{" +
                "listaId='" + listaId + '\'' +
                ", nombreCancion='" + nombreCancion + '\'' +
                ", nombreCreador='" + nombreCreador + '\'' +
                '}';
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        PlaylistVO that = (PlaylistVO) o;
        return Objects.equals(listaId, that.listaId) &&
                Objects.equals(nombreCancion, that.nombreCancion) &&
                Objects.equals(nombreCreador, that.nombreCreador);
    }

    @Override
    public int hashCode() {
        return Objects.hash(listaId, nombreCancion, nombreCreador);
    }
    
}
