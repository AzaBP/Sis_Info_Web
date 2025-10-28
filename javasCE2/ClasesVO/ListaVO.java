package javasCE2.ClasesVO;
import java.io.Serializable;

public class ListaVO implements Serializable {
    private static final long serialVersionUID = 1L;

    private String listaId;

    public ListaVO(){}

    public ListaVO(String listaId) {
        this.listaId = listaId;
    }

    public String getListaId() {
        return listaId;
    }

    public void setListaId(String listaId) {
        this.listaId = listaId;
    }

    @Override
    public String toString() {
        return "ListaVO{" +
                "ListaId='" + listaId + '\'' +
                '}';
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        ListaVO listaVO = (ListaVO) o;
        return listaId != null && listaId.equals(listaVO.listaId);
    }

    @Override
    public int hashCode() {
        return listaId != null ? listaId.hashCode() : 0;
    }
}
