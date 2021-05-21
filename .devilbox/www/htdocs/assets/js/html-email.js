class HtmlEmail extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: 'open' });

    let emailContent;
    try {
      emailContent = window.atob(this.dataset.content);
    } catch (error) {
      console.log(error);
      return;
    }

    const container = document.createElement('div');
    container.innerHTML = emailContent;

    this.shadowRoot.appendChild(container);
  }
}

customElements.define('html-email', HtmlEmail);
