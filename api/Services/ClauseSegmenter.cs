using System.Text.RegularExpressions;

namespace Comparador.Api.Services;

public static partial class ClauseSegmenter
{
    private const int MinimumStructuredMatches = 2;

    public static IReadOnlyList<string> Segment(string text)
    {
        var matches = ClauseHeaderRegex().Matches(text).Cast<Match>().ToArray();
        if (matches.Length < MinimumStructuredMatches)
        {
            matches = NumberedLineRegex().Matches(text).Cast<Match>().ToArray();
        }

        return matches.Length >= MinimumStructuredMatches
            ? SplitAtMatches(text, matches)
            : text.Split("\n\n", StringSplitOptions.RemoveEmptyEntries | StringSplitOptions.TrimEntries);
    }

    private static IReadOnlyList<string> SplitAtMatches(string text, IReadOnlyList<Match> matches)
    {
        var clauses = new List<string>(matches.Count);
        for (var index = 0; index < matches.Count; index++)
        {
            var end = index + 1 < matches.Count ? matches[index + 1].Index : text.Length;
            var clause = text[matches[index].Index..end].Trim();
            if (clause.Length > 0)
            {
                clauses.Add(clause);
            }
        }

        return clauses;
    }

    [GeneratedRegex(@"^\s*(CL[ÁA]USULA|ART[ÍI]CULO)\s+\S.*$", RegexOptions.IgnoreCase | RegexOptions.Multiline)]
    private static partial Regex ClauseHeaderRegex();

    [GeneratedRegex(@"^\s*\d{1,3}[.)]\s+\S", RegexOptions.Multiline)]
    private static partial Regex NumberedLineRegex();
}