"""Generate the historical Python reference for the Phase 5.1 parity batch."""
import hashlib
import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
sys.path.insert(0, str(ROOT / "service"))

from app.extraction import extract_text
from app.segmentation import segment_clauses


def normalize(value: str) -> str:
    return " ".join(value.split())


def main() -> None:
    manifest_path = ROOT / "tools" / "phase5-parity" / "manifest.json"
    output_path = ROOT / "artifacts" / "phase5" / "python-reference.json"
    manifest = json.loads(manifest_path.read_text(encoding="utf-8"))
    results = []

    for relative_path in manifest["documents"]:
        path = ROOT / relative_path
        result = {
            "path": relative_path,
            "sha256": hashlib.sha256(path.read_bytes()).hexdigest(),
            "status": "error",
            "text": "",
            "clauses": [],
            "error": None,
        }
        try:
            text = extract_text(path.read_bytes(), path.suffix)
            clauses = segment_clauses(text)
            result.update(
                status="processed",
                text=text,
                clauses=clauses,
                normalized_text=normalize(text),
                normalized_clauses=[normalize(clause) for clause in clauses],
            )
        except Exception as exception:  # noqa: BLE001 - report every document outcome.
            result["error"] = f"{type(exception).__name__}: {exception}"
        results.append(result)

    output_path.parent.mkdir(parents=True, exist_ok=True)
    output_path.write_text(
        json.dumps(
            {
                "dataset": manifest["dataset"],
                "runtime": "python-legacy",
                "documents": results,
            },
            ensure_ascii=False,
            indent=2,
        ),
        encoding="utf-8",
    )


if __name__ == "__main__":
    main()
