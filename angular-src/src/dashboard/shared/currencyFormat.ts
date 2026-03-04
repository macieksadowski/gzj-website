import { Directive, ElementRef, HostListener, Renderer2 } from '@angular/core';

export function parseCurrencyAmount(value: unknown): number {
  if (typeof value === 'number') {
    return Number.isFinite(value) ? value : 0;
  }

  if (typeof value === 'string') {
    const normalized = value
      .replace(/\s/g, '')
      .replace('zł', '')
      .replace('PLN', '')
      .replace(',', '.')
      .replace(/[^0-9.-]/g, '');
    const parsed = parseFloat(normalized);
    return Number.isFinite(parsed) ? parsed : 0;
  }

  return 0;
}

export function formatCurrencyAmount(value: unknown, locale: string = 'pl-PL', currency: string = 'PLN'): string {
  return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency
  }).format(parseCurrencyAmount(value));
}

@Directive({
    selector: '[currencyFormat]'
})
export class CurrencyFormatDirective {
    private locale: string = 'pl-PL';
    private currency: string = 'PLN';

    constructor(private el: ElementRef, private renderer: Renderer2) {}

    ngOnInit() {
        this.formatValue();
    }

    @HostListener('input', ['$event'])
    onInput(event: any) {
        let value = event.target.value.replace(/[^\d,.-]/g, '');
        value = value.replace(',', '.');

        this.renderer.setProperty(this.el.nativeElement, 'value', value);
    }
    
      @HostListener('blur')
      onBlur() {
        this.formatValue();
      }

      private formatValue() {
        const formatted = formatCurrencyAmount(this.el.nativeElement.value, this.locale, this.currency);
        
        this.renderer.setProperty(this.el.nativeElement, 'value', formatted);
      }
    }