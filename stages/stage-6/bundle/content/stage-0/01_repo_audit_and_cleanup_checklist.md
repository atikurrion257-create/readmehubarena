# STAGE 0.1 — Repository Audit & Cleanup Checklist

**Audit Date:** 2026-11-19 (UTC)  
**Active Skills:** `readmehub-context-orchestrator` + `readmehub-affiliate-strategist`  
**Inspected Source Repository:** `https://github.com/atikurrion257-create/readmehubinstructionsfile`  
**Working Build Repository:** `https://github.com/atikurrion257-create/readmehubarena` (`arena/86565b86-readmehubarena`)  
*(Note: Deliverables are stored under `stages/stage-N/` rather than `build/` so Arena's turn-end git snapshot persists all files across turns.)*

---

## 1. Repository Inspection Summary

1. **Source Repository Structure:** `atikurrion257-create/readmehubinstructionsfile` contains **31 extracted Markdown source files** at the repository root (with original folder hierarchy encoded via `__`), plus 3 repository entry-point files (`AGENTS.md`, `README.md`, and `AI_AGENT_CONTEXT.md`). Its manifest (`00_README_AND_FILE_MANIFEST.md`) catalogues all 59 items from the original `ReadMeHub_Complete_Project_Files` archive.
2. **Canonical Workspace Sync:** All 13 clean canonical reference files (plus `ReadMeHub_Unified_Master_Build_Prompt_v3.md`) are persisted in `/references/` inside this workspace without any duplicate `*(1)` files, raw paste dumps, or quarantined third-party prompt variants.
3. **Missing Tier-2 Source File Flagged:** `ReadMeHub_WordPress_Production_Build_Blueprint_v1.xlsx` (Part 2 Conflict-Resolution Order #2) is **NOT present** in `readmehubinstructionsfile`, `readmehubarena`, or session uploads. Zero workbook values are guessed; workbook-dependent items (`/hosting/wordpress/small-business/` slug confirmation, stale `"Formalized"` spreadsheet cells, and the 38-row Keyword Universe table) are logged in the Conflict Log and Blockers list.
4. **Stage-1 Hands-On Testing Logs Check:** **Zero (`0`) dated Stage-1 testing logs, completed worksheets, or dated benchmark screenshots** exist anywhere in the repository. Per Part 5.2, all commercial pages default strictly to **Mode A (`mode_a_research` — Stage-0 Research Draft)** with `BLOCK PUBLISH` enforcement until dated Stage-1 worksheets are supplied.

---

## 2. Canonical Files Read & Verified (`KEEP` Set in `/references/`)

| Canonical Reference File | Repo Source Filename | Size (Bytes) | SHA-256 Prefix | Disposition |
|---|---|---:|---|---|
| `references/01_READMEHUB_PROJECT_CONTEXT.md` | `01_Project_Documents__Claude_Project_MD_Files__01_READMEHUB_PROJECT_CONTEXT.md` | 2,873 | `364a40ff8cc8…` | **READ & KEPT** (paired with `readmehub-project-file-corrections.md`) |
| `references/02_READMEHUB_DESIGN_SYSTEM.md` | `01_Project_Documents__Claude_Project_MD_Files__02_READMEHUB_DESIGN_SYSTEM.md` | 1,763 | `393423fd992b…` | **READ & KEPT** (primary reference token list) |
| `references/03_ELEMENTOR_IMPLEMENTATION_RULES.md` | `01_Project_Documents__Claude_Project_MD_Files__03_ELEMENTOR_IMPLEMENTATION_RULES.md` | 2,360 | `5957adda9577…` | **READ & KEPT** (paired with `readmehub-project-file-corrections.md`) |
| `references/04_WORDPRESS_ACF_CONTENT_MAP.md` | `01_Project_Documents__Claude_Project_MD_Files__04_WORDPRESS_ACF_CONTENT_MAP.md` | 2,195 | `7f5333fc5e21…` | **READ & KEPT** (confirms zero pre-existing ACF slugs) |
| `references/05_IMPLEMENTATION_LOG_AND_QA.md` | `01_Project_Documents__Claude_Project_MD_Files__05_IMPLEMENTATION_LOG_AND_QA.md` | 2,464 | `80a7dd3d5372…` | **READ & KEPT** (QA log target for Stage 7 handoff) |
| `references/06_CLAUDE_PROJECT_INSTRUCTIONS.md` | `01_Project_Documents__Claude_Project_MD_Files__06_CLAUDE_PROJECT_INSTRUCTIONS.md` | 3,421 | `cc72106ef3b9…` | **READ & KEPT** *(verified present despite stale note in `SKILL (1).md`)* |
| `references/07_PER_SECTION_TASK_PROMPT.md` | `01_Project_Documents__Claude_Project_MD_Files__07_PER_SECTION_TASK_PROMPT.md` | 2,147 | `5d5e217a9454…` | **READ & KEPT** (paired with `readmehub-project-file-corrections.md`) |
| `references/08_READMEHUB_PAGE_TEMPLATE_SCOPE.md` | `01_Project_Documents__Claude_Project_MD_Files__08_READMEHUB_PAGE_TEMPLATE_SCOPE.md` | 3,078 | `47a10521b5d7…` | **READ & KEPT** (baseline 17-item scope extended to 20 templates in v3) |
| `references/connector-tools (3).md` | `06_Additional_Project_Files__connector-tools (3).md` | 5,062 | `f6a2dbb75c7b…` | **READ & KEPT** (latest Royal MCP read/draft probe dated `2026-10-23`) |
| `references/readmehub-project-file-corrections.md` | `01_Project_Documents__readmehub-project-file-corrections.md` | 4,836 | `e5f88e9c50c3…` | **READ & KEPT** (owner confirmation `2026-10-23`: `www.readmehub.com` = PRODUCTION) |
| `references/readmehub-homepage-plan.md` | `01_Project_Documents__readmehub-homepage-plan.md` | 6,226 | `7b932bf34a2f…` | **READ & KEPT** (verified `2026-10-23` WP/plugin/kit runtime snapshot) |
| `references/ReadMeHub — Elementor Free-First Website Specification.md` | `01_Project_Documents__ReadMeHub — Elementor Free-First Website Specification.md` | 84,787 | `cb3fe055dea7…` | **READ & KEPT** (Tier-4 fallback architectural specification) |
| Canonical Skill Files (`SKILL.md`, `SKILL (1).md`, `SKILL (3).md`, `SKILL (4).md`) | `03_Elementor_and_Skills__SKILL*.md` (deduplicated) | 3,080–4,185 | Verified | **READ** (`wp-change-safety`, `readmehub-project-pack`, `design-system-to-elementor`, `elementor-qa`) |

---

## 3. Mandatory Repository Cleanup Checklist (`readmehubinstructionsfile`)

### A. DELETE — Exact Byte-for-Byte Duplicates & Superseded Connector Drafts
- `03_Elementor_and_Skills__SKILL (3)(1).md` (`3,080 B`, SHA-256 `35577c9d…` — exact duplicate of `SKILL (3).md`)
- `03_Elementor_and_Skills__SKILL (4)(1).md` (`3,745 B`, SHA-256 `32bd4251…` — exact duplicate of `SKILL (4).md`)
- `02_Master_Prompts__ReadMeHub_Master_Website_Build_Prompt(1).md` (`37,900 B`, SHA-256 `8bc59ec3…` — exact 2nd copy of `ReadMeHub_Master_Website_Build_Prompt.md`)
- `06_Additional_Project_Files__connector-tools (1).md` (`3,040 B`, SHA-256 `47904fdd…` — duplicate of `connector-tools.md`)
- `06_Additional_Project_Files__connector-tools (2)(1).md` (`4,339 B`, SHA-256 `b08e67d5…` — duplicate of `connector-tools (2).md`)
- *(Recommended)* `06_Additional_Project_Files__connector-tools.md` & `connector-tools (2).md` — superseded by `connector-tools (3).md`.

### B. ARCHIVE / QUARANTINE — Non-Claude Prompts, Raw Paste Dumps & Legacy Archives
- `02_Master_Prompts__Manus_AI_Native_Elementor_Transfer_Ready_Build_Prompt.md` & `02_Master_Prompts__Manus_AI_ReadMeHub_Full_Elementor_Build_Master_Prompt.md`
- `01_Project_Documents__ReadMeHub Research and Architecture Foundation.md` (Manus AI, `2026-09-06`)
- `ReadMeHub_ChatGPT_Deep_Research_Execution_Mode.txt`, `ReadMeHub_Gemini_Deep_Research_Execution_Mode.txt`, `ReadMeHub_Perplexity_Deep_Research_Execution_Mode.txt`
- `Pasted text(2).txt` & `Pasted text(3).txt`
- `05_Source_Archives/*.zip` (8 legacy ZIP bundles)
- Pre-v3 prompts (`ReadMeHub_Master_Website_Build_Prompt.md`, `ReadMeHub_Complete_Remaining_Website_Build_Prompt.md`, `ReadMeHub_2026_Claude_Final_Synthesis_Prompt.md`, `ReadMeHub_2026_Masterstroke_Research_Prompt.md`)

### C. Permanently Quarantined Fabricated Demo Metrics
- ❌ `"420+ B2B tools tested"`, `"3,800+ synthetic benchmarks"`, `"1,840+ reviews"`, fake author personas, unverified star ratings, and unverified discount codes.
