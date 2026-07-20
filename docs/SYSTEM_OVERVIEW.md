# PANDA System — Overview

PANDA is a workflow system for processing **PANs** (Personnel Action Notices) — requests to change something about an employee's status: regularization, salary alignment, wage order, promotion, transfer, allowances, and similar HR actions. A PAN moves through a fixed chain of people before it's considered final: a Requestor raises it, a Division Head approves it, an HR Preparer fills in the official paperwork, an HR Approver checks it, and a Final Approver signs off. Admin staff manage who can do what.

Access to each stage is controlled independently per user — a person can hold any combination of the five stage permissions (Requestor, Division Head, HR Preparer, HR Approver, Final Approver) at once, so the same person could, for example, be both a Requestor and a Division Head. Separately, a person can also be marked as an **HR Head** (elevated privileges within HR Preparer specifically) or as an **Admin** (full access to the administration screens), and independently again as a **Confidentiality Approver** (able to see the small number of PANs marked highly confidential). These three extra flags are unrelated to each other and unrelated to the five stage permissions.

---

## User types, in plain terms

- **Requestor** — the person who initiates a PAN for an employee.
- **Division Head** — approves or returns a PAN before it goes to HR, for the department(s) they head.
- **HR Preparer** — fills in the official HR paperwork for an approved PAN.
- **HR Head Preparer** — same role, but with the added ability to handle the small subset of PANs flagged as highly confidential ("Manila"); an ordinary HR Preparer cannot open those.
- **HR Approver** — a second check after HR preparation, before final sign-off.
- **Final Approver** — gives the last approval, after which the PAN is considered decided.
- **Confidentiality Approver** — a cross-department role (not tied to any one department) that exclusively handles the "Manila"-tagged confidential PANs at the division-head stage, regardless of which department they belong to.
- **Admin** — manages user accounts, module access, department assignments, and can review activity/audit logs.

A single person can be several of these at once. Someone can be a Division Head for more than one department, and a department can have more than one Division Head (co-heads are supported, not just one person per department).

---

## Requestor module

**What they see:** a personal list of PAN requests for the department(s) they're registered to submit for, with search and status filtering (e.g. "in progress" vs "completed"), and a shortcut to start a new request.

**Request form fields:**
- Employee — chosen from a dropdown limited to employees in the requestor's own department(s); selecting one auto-fills the employee's ID and department.
- Type of Action — a dropdown of the supported PAN types (regularization, salary alignment, wage order, lateral transfer, developmental assignment, interim allowance, promotion, training status, confirmation of appointment, change of position, discontinuance of allowance, confirmation of development assignment, other allowances).
- Justification — free text, optional.
- Supporting document — a PDF attachment (required to formally submit; optional while saved as a draft).

**What they can do:**
- Save as a draft without submitting.
- Submit directly to the Division Head.
- Edit and resubmit a draft.
- Delete a draft outright.
- If a request is sent back by the Division Head for correction, replace the attachment and resubmit it, or withdraw it entirely.
- Once submitted (out of draft), the request becomes read-only to the requestor — no further edits until/unless it comes back for correction.

---

## Division Head module

**What they see:** a queue of PAN requests for the department(s) they head, spanning every stage of the workflow (not just the ones awaiting their action) so they can track progress end-to-end, with search and status filtering. Requests marked as confidential "Manila" are invisible here unless the viewer is specifically the designated Confidentiality Approver — a regular Division Head never sees them, even for their own department.

**What they can do**, only while a request is freshly submitted and awaiting their decision:
- Approve it, sending it forward to HR Preparation.
- Return it to the Requestor with a reason (a preset list of reasons, or a custom one, plus optional details), sending it back for correction.

Once a request has moved past this stage, the Division Head can still view it in their queue but can no longer act on it or edit any of its fields.

---

## HR Preparer module

**What they see:** a queue of PANs that have been approved by a Division Head and are awaiting HR paperwork, plus PANs already further along so they can be tracked, with search/status filtering. Confidentiality-tagged requests show a small color indicator (visible to HR Head Preparers only) — purple for the confidential tag, blue for the routine one, gray if untagged.

**Before any paperwork can begin, the PAN must be tagged** — either as the routine confidentiality level or as the restricted "Manila" one. Any HR Preparer, ordinary or Head, can apply this initial tag. What happens next depends on which tag was chosen and who applied it:
- An ordinary HR Preparer tagging it routine → stays on the form and continues preparing it themselves.
- An ordinary HR Preparer tagging it "Manila" → is sent back to the list, and that record becomes permanently locked to them going forward — their View button for it turns inactive and they can no longer open it at all.
- An HR Head Preparer tagging it routine → sent back to the list; they can still open/view it if needed, but by process it's the ordinary preparer's job to finish it.
- An HR Head Preparer tagging it "Manila" → stays on the form and continues preparing it themselves.

In short: routine PANs are worked by ordinary HR Preparers, while confidential ones are worked exclusively by an HR Head Preparer. An HR Head Preparer can view every PAN regardless of tag; an ordinary HR Preparer cannot open a confidential one at all once tagged.

**Preparation form fields:**
- Date Hired.
- Employment Status (a fixed list — probationary, regular, project-based, fixed-term, casual, part-time, seasonal); this locks to a read-only pre-filled value automatically when there's a matching prior completed request for the same employee to carry the status forward consistently.
- Division/Department — carried over automatically from the request, not editable here.
- Effectivity dates (from/to).
- Wage number — only shown for Wage Order actions.
– An "Action Reference" table listing exactly what is changing — rows like section, place of assignment, immediate head, position, job level, basic pay, and (for regularization specifically) leave credits, each showing a From value and a To value. Additional allowance line items (communication, meal, living, transportation, clothing, fuel, management, developmental assignment, professional, interim, training, mancom, or a generic allowance) can be added or removed freely as extra From/To rows.
- Remarks — free text.

When there's a prior PAN on file for the same employee, its "To" values are automatically carried in as this form's starting "From" values, so the change history chains from one PAN to the next instead of being re-typed.

**What they can do:**
- Submit the prepared PAN, sending it to the Division Head for confirmation.
- If the Division Head disputes/returns it for resolution, revise and resubmit.
- Mark an approved PAN as served, and later as filed, once it's been fully carried out.
- Mark an approved PAN as unserved instead (with a reason — AWOL, resigned, terminated, or a custom reason) if it couldn't be carried out.
- Delete/void a PAN that's still awaiting preparation or resolution, with a mandatory reason recorded.
- Start a brand-new follow-up PAN cycle for the same employee once a prior one has been filed (e.g. a new action after the last one closed out), carrying over the employee/department details automatically.

---

## HR Approver module

**What they see:** a separate queue, filtered to PANs currently awaiting HR-level approval (and, for reference, ones further along). No confidentiality distinction applies at this stage — everything here is visible to anyone with HR Approver access.

**What they can do:**
- Approve a PAN that's awaiting HR approval, sending it forward to the Final Approver.
- Return it to the HR Preparer with a mandatory reason, sending it back a step for rework (not all the way back to the Requestor).

---

## Final Approver module

**What they see:** a queue of PANs awaiting final sign-off (plus completed ones for reference), with the ability to act on requests individually **or in bulk** — approving or rejecting many at once, either by selecting them individually or by targeting all requests of a particular action type at once.

**What they can do:**
- Give final approval, which closes out the approval stage of the PAN. If the action type is a Regularization, the employee's status is automatically finalized as "Regular" as part of this approval, overriding whatever was tentatively set earlier.
- Reject/return a PAN with a mandatory reason — this sends it all the way back to the HR Preparer stage for rework, not just one step back.
- Bulk-approve or bulk-reject follow the same rules as the single-record actions.

---

## Admin module

Reached through a dedicated admin page. One section (a general employee directory, with the ability to add, edit, or remove employee records, plus bulk import/export via spreadsheet) is visible to any logged-in user who reaches the page; the remaining sections are intended for Admin users only:

- **User Access management** — the central screen for controlling who can do what:
  - Grant or revoke each of the five stage permissions (Requestor, Division Head, HR Preparer, HR Approver, Final Approver) per person, individually.
  - Assign which department(s) a person can submit requests for, and separately which department(s) they head — the two are independent; someone can head a department without being a requestor there, or vice versa, and a department can have multiple heads.
  - Toggle the HR Head flag, the Admin flag, and the Confidentiality Approver flag for a person.
  - Set a person's farm/site and job position.
  - Upload or replace a person's e-signature image, used on printed/approved PAN documents.
- **Access Logs** — a read-only record of login attempts (who, when, from where, success or failure).
- **Audit Trail** — a read-only, running log of significant actions taken across the system (who did what, in which module, when) — this is the log that captures things like confidentiality tagging, approvals, returns, and deletions mentioned in the module sections above.

There's also an unused "run a backup now" control built but not currently placed anywhere reachable in the interface, and scheduled, automatic backups running independently in the background (daily database backup, cleanup of old backups, and a health check that the backups are still landing correctly and aren't stale or oversized).

---

## How a PAN typically flows, stage to stage

Draft (optional) → submitted to Division Head → approved by Division Head → prepared by HR (after being tagged for confidentiality) → confirmed by Division Head → approved by HR Approver → approved by Final Approver → served → filed.

Branch points along the way:
- A Division Head can return a submitted request to the Requestor for correction, or reject it outright.
- An HR Approver or the Final Approver can send a PAN back to HR Preparation for rework rather than approving it.
- HR can mark an approved PAN "unserved" instead of "served" if it wasn't actually carried out.
- A Requestor can withdraw a request that's been returned to them rather than resubmitting it.
- A PAN still awaiting preparation (or sent back for resolution) can be deleted/voided outright by HR, with a reason on record.
- Once filed, a fresh follow-up PAN can be started for the same employee, continuing the change history.

Confidential ("Manila") PANs follow the same overall path, but two extra restrictions apply throughout: only an HR Head Preparer can prepare one, and only the designated Confidentiality Approver — not the department's regular Division Head — can act on one at the division-head confirmation stage.

---

## Data model, in plain terms

The system's data is organized around these concepts:

- **People/accounts** — one record per user, holding their name, site/farm, job position, the five stage permissions, the HR Head/Admin/Confidentiality-Approver flags, and their e-signature image. Login identity and basic profile details for the wider staff directory are pulled in from an external company system separately from this record.
- **Departments** — a small reference list of department names, each of which can have any number of people assigned as requestors and any number of people assigned as heads, independently of each other.
- **Employee directory** — the roster of employees a PAN can be raised for: name, employee ID, site/farm, department, and position.
- **PAN requests** — the core record of each request: which employee it's for, what type of action, its justification, its attachment, its current stage/status, its confidentiality tag, its department, and who acted as requestor/division head/HR/approver along the way (these participant references are stored in a protected, non-reversible form for privacy). A generated reference number identifies each request.
- **PAN preparation details** — a second, linked record created once HR preparation begins: dates, employment status, the detailed From/To change table, remarks, and who prepared/approved it. Sensitive preparation fields here are stored in protected form as well.
- **Correction/return log** — a running history of every time a request was sent back a stage, with the reason given and where it came from, so the full back-and-forth on a request can be traced.
- **Login/access log** — a record of sign-in attempts, successful or not.
- **Audit trail** — a running log of notable actions taken across every module.
- **Expiry notifications** — internal reminders generated for HR when an allowance tied to a PAN is approaching its expiry date, so it can be acted on in time.

---

## A few things worth knowing that don't fit neatly above

- The permission to *reach* a module (the five stage toggles) and the permission to *see the admin pages* (the Admin flag) are checked in different ways in a couple of spots, and a couple of admin-adjacent screens are gated by the wrong module permission — worth a closer look before relying on those boundaries for anything sensitive.
- One "return/reject" action that exists in the system isn't currently reachable from any button in the interface — it works if triggered, but nothing in the UI currently offers it.
- The deeper protection against a confidential PAN being opened via a direct link (rather than through the disabled button in the list) is not yet built — this was flagged earlier as a follow-up and is still open.
