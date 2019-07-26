import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { map, tap, catchError } from 'rxjs/operators';
import { Profile } from '../../shared/interfaces';

interface myData {
  message: string,
  success: boolean
}

interface isLoggedIn {
  status: boolean
}

@Injectable({
  providedIn: 'root'
})
export class PerfilService {

  public urlApi: string = 'http://localhost:3000/painel-perfil/backend/php/api';

  constructor(private http: HttpClient) { }

  private handleError(error: any) {
    console.error(error);
    return throwError(error);
  }

  /*
    getUsuarios(): Observable<Profile[]> {
      return this.http.get<Profile[]>(`${this.urlApi}/perfil`);
    }*/

  getProfile(id: number): Observable<Profile> {
    return this.http.get<Profile>(`${this.urlApi}/perfil/${id}`);
  }

  crateProfile(user): Observable<Profile> {
    return this.http.post<Profile>(`${this.urlApi}/perfil`, user);
  }

  deleteProfile(id: number): Observable<Profile> {
    return this.http.delete<Profile>(`${this.urlApi}/perfil/${id}`).pipe(
      tap(data => console.log(data)),
      catchError(this.handleError)
    );
  }

  updateProfile(user: Profile): Observable<any> {
    return this.http.put<Profile>(`${this.urlApi}/perfil/update/${user.id}`, user).pipe(
      //  tap(data => console.log(data)),
      catchError(this.handleError)
    );
  }
}
