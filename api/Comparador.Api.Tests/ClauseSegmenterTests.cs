using Comparador.Api.Services;

namespace Comparador.Api.Tests;

public class ClauseSegmenterTests
{
    [Fact]
    public void SegmentaPorEncabezadosDeClausula()
    {
        const string text = "CLÁUSULA PRIMERA\nObjeto\n\nARTÍCULO 2\nJornada de trabajo";

        var clauses = ClauseSegmenter.Segment(text);

        Assert.Equal(2, clauses.Count);
        Assert.StartsWith("CLÁUSULA PRIMERA", clauses[0]);
        Assert.StartsWith("ARTÍCULO 2", clauses[1]);
    }

    [Fact]
    public void UsaParrafosCuandoNoHayEstructuraNumerada()
    {
        const string text = "Primer párrafo de la convención.\n\nSegundo párrafo de la convención.";

        var clauses = ClauseSegmenter.Segment(text);

        Assert.Equal(["Primer párrafo de la convención.", "Segundo párrafo de la convención."], clauses);
    }
}