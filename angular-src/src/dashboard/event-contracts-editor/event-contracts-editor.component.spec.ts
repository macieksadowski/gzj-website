import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EventContractsEditorComponent } from './event-contracts-editor.component';

describe('EventContractsEditorComponent', () => {
  let component: EventContractsEditorComponent;
  let fixture: ComponentFixture<EventContractsEditorComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EventContractsEditorComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EventContractsEditorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
