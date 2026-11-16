const contactDialog = document.getElementById("contactMailDialog");

if (typeof contactDialog?.showModal === "function") {
    const addressField = document.getElementById("contact-mail-address");
    const subjectText = document.getElementById("contact-mail-subject");
    const copyStatus = document.getElementById("contact-mail-status");
    const nativeLink = document.getElementById("contact-mail-native");
    const gmailLink = document.getElementById("contact-mail-gmail");
    const outlookLink = document.getElementById("contact-mail-outlook");
    let contactTrigger = null;

    document.querySelectorAll('a[href^="mailto:"]').forEach(link => link.setAttribute("aria-haspopup", "dialog"));

    document.addEventListener("click", event => {
        if (event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        const link = event.target.closest('a[href^="mailto:"]');
        if (!link || contactDialog.contains(link)) return;

        const mail = new URL(link.href);
        if (!mail.pathname) return;
        const recipient = decodeURIComponent(mail.pathname);
        const subject = mail.searchParams.get("subject");
        const gmail = new URL("https://mail.google.com/mail/?view=cm&fs=1");
        const outlook = new URL("https://outlook.live.com/mail/0/deeplink/compose");
        gmail.searchParams.set("to", recipient);
        outlook.searchParams.set("to", recipient);
        if (subject) {
            gmail.searchParams.set("su", subject);
            outlook.searchParams.set("subject", subject);
        }

        event.preventDefault();
        contactTrigger = link;
        addressField.value = recipient;
        subjectText.textContent = subject ? `Sujet · ${subject}` : "";
        subjectText.hidden = !subject;
        copyStatus.textContent = "";
        nativeLink.href = link.href;
        gmailLink.href = gmail.href;
        outlookLink.href = outlook.href;
        contactDialog.showModal();
    });

    document.getElementById("contact-mail-copy").addEventListener("click", async () => {
        try {
            await navigator.clipboard.writeText(addressField.value);
            copyStatus.textContent = "Adresse copiée.";
        } catch {
            addressField.focus();
            addressField.select();
            copyStatus.textContent = "Copie l’adresse sélectionnée.";
        }
    });

    contactDialog.addEventListener("close", () => {
        contactTrigger?.focus({preventScroll: true});
        contactTrigger = null;
    });
}
