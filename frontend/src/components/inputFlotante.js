export default function inputFlotante(e) {
    const type = e.type;
    const el = e.target; // el input
    const wrapper = el.closest(".input-wrapper");
    const label = wrapper?.querySelector(`label[for="${el.id}"]`);
    const spanLabel = label?.querySelector(`span`);
    if (type === "focus") {
        el?.classList.add("clickled_input");
        label?.classList.add("clickled_label");
        requestAnimationFrame(() => {
            const wrapperRect = wrapper.getBoundingClientRect();
            const labelRect = label.getBoundingClientRect();
            const anchoRestante = wrapperRect.width - labelRect.width;
            spanLabel.style.width = `${anchoRestante}px`;
        });
    }

    if (type === "blur") {
        if (!el.value.trim()) {
            spanLabel.style.width = "0px"; // opcional: reset
            el?.classList.remove("clickled_input");
            label?.classList.remove("clickled_label");
        }
    }

    if (type === "change") {
        return e.target.value;
    }

}