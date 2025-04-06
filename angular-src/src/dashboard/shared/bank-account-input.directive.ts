import { Directive, ElementRef, HostListener } from '@angular/core';
import { ControlContainer, FormControl } from '@angular/forms';

@Directive({
  selector: '[appBankAccountInput]',
  standalone: true
})
export class BankAccountInputDirective {
  constructor(
    private el: ElementRef<HTMLInputElement>,
    private controlContainer: ControlContainer
  ) {}

  @HostListener('input')
  onInput(): void {
    const input = this.el.nativeElement;
    const raw = input.value.replace(/\D/g, '').slice(0, 26); // 26 max digits
    const formatted = this.formatAccountNumber(raw);
    input.value = formatted;

    const controlName = input.getAttribute('formControlName');
    const control = this.controlContainer.control?.get(controlName!);
    control?.setValue(formatted, { emitEvent: false });
  }

  @HostListener('keydown', ['$event'])
  onKeyDown(e: KeyboardEvent) {
    const allowed = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Delete', 'Tab'];
    if (!allowed.includes(e.key) && !/^\d$/.test(e.key)) {
      e.preventDefault();
    }
  }

  private formatAccountNumber(value: string): string {
    // 00 0000 0000 0000 0000 0000 0000
    const groups = [
      value.slice(0, 2),
      value.slice(2, 6),
      value.slice(6, 10),
      value.slice(10, 14),
      value.slice(14, 18),
      value.slice(18, 22),
      value.slice(22, 26),
    ];

    return groups.filter(group => group.length > 0).join(' ');
  }
}
