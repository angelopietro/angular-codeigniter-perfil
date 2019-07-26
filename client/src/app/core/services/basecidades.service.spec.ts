import { TestBed } from '@angular/core/testing';

import { BasecidadesService } from './basecidades.service';

describe('BasecidadesService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: BasecidadesService = TestBed.get(BasecidadesService);
    expect(service).toBeTruthy();
  });
});
