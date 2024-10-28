package com.cyberjawara.chall.web.javabox.exception;

import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.context.request.WebRequest;
import org.springframework.web.servlet.mvc.method.annotation.ResponseEntityExceptionHandler;

import java.io.PrintWriter;
import java.io.StringWriter;
import java.util.HashMap;
import java.util.Map;

@ControllerAdvice
public class ControllerAdvisor extends ResponseEntityExceptionHandler {

  @ExceptionHandler(Exception.class)
  public ResponseEntity<Object> handleAllExceptions(Exception ex, WebRequest request) {
    return buildErrorResponse(ex, HttpStatus.INTERNAL_SERVER_ERROR);
  }

  // Helper method to convert an exception into a ResponseEntity
  public ResponseEntity<Object> buildErrorResponse(Exception ex, HttpStatus status) {
    StringWriter sw = new StringWriter();
    ex.printStackTrace(new PrintWriter(sw));
    String stackTrace = sw.toString();

    Map<String, Object> response = new HashMap<>();
    response.put("message", ex.getMessage());
    response.put("stackTrace", stackTrace);
    response.put("status", status.value());
    response.put("error", status.getReasonPhrase());

    HttpHeaders headers = new HttpHeaders();
    headers.setContentType(MediaType.APPLICATION_JSON);

    return new ResponseEntity<>(response, headers, status);
  }
}
