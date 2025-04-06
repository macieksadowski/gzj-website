declare module 'dual-listbox' {
    export default class DualListbox {
      constructor(selector: string | HTMLSelectElement, options?: any);
      selected: HTMLOptionElement[];
      available: HTMLOptionElement[];
      add_all_button: HTMLButtonElement;
      remove_all_button: HTMLButtonElement;
      selectedList: HTMLUListElement;
      availableList: HTMLUListElement;
    }
  }
  