import { Injectable } from '@angular/core';
import { throwError, Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { map, tap, catchError } from 'rxjs/operators';
import { Estados, Cidades } from 'src/app/shared/interfaces';

@Injectable({
  providedIn: 'root'
})
export class BasecidadesService {


  public urlApi: string = 'http://localhost:3000/painel-perfil/backend/php/api';

  constructor(private http: HttpClient) { }

  private handleError(error: any) {
    console.error(error);
    return throwError(error);
  }

  getEstados(): Observable<Estados> {
    return this.http.get<Estados>(`${this.urlApi}/estados/`);
  }

  getCidades(id: number): Observable<Cidades> {
    return this.http.get<Cidades>(`${this.urlApi}/estados/cidades/${id}`);
  }

}
