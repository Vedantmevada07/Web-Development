<label>Event Name</label>
<input
    type="text"
    name="event_name"
    maxlength="150"
    value="<?= htmlspecialchars($event["event_name"] ?? "", ENT_QUOTES, "UTF-8") ?>"
    required
>

<label>Event Date</label>
<input
    type="date"
    name="event_date"
    value="<?= htmlspecialchars($event["event_date"] ?? "", ENT_QUOTES, "UTF-8") ?>"
    required
>

<label>Location</label>
<input
    type="text"
    name="location"
    maxlength="150"
    value="<?= htmlspecialchars($event["location"] ?? "", ENT_QUOTES, "UTF-8") ?>"
    required
>

<label>Description</label>
<textarea name="description" rows="4" required><?= htmlspecialchars($event["description"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>

<label>Event Poster (JPG, JPEG, PNG, WEBP; maximum 2 MB)</label>
<input
    type="file"
    name="poster"
    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
>